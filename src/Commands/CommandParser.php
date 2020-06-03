<?php

namespace Germix\Api\Commands;

use Germix\Api\Commands\Command;
use Germix\Api\Commands\Exceptions\CanNotOpenCommandsFileException;
use Germix\Api\Commands\Exceptions\InvalidCommandException;
use Germix\Api\Commands\Exceptions\InvalidEndpointException;
use Germix\Api\Commands\Exceptions\MethodNotAllowedException;
use Germix\Api\Commands\Exceptions\UnexpectedTokenException;

/**
 * Esta clase es el analizador sintáctico del archivo de configuración de comandos asociados a la URL, y devuelve el comando
 *
 * @author Germán Martínez
 *
 */
class CommandParser
{
    /**
     * @var CommandToken
     */
    private $tok;

    /**
     * @var CommandLexer
     */
    private $lex;

    /**
     * CommandParser constructor.
     */
    public function __construct()
    {
    }

    /**
     * Parser el archivo de definición de comandos
     *
     * @param string    $fileName           Nombre del archivo que contiene la configuración de comandos
     * @param string    $method             Método de la consulta
     * @param array     $urlParts           Arreglo de las partes que componen a la URL
     * @param array     $urlParameters      Lista de parámetros en la URL
     *
     * @return Command
     *
     * @throws MethodNotAllowedException
     * @throws InvalidEndpointException
     * @throws UnexpectedTokenException
     * @throws CanNotOpenCommandsFileException
     * @throws InvalidCommandException
     *
     */
    public function parse($fileName, $method, array $urlParts, array &$urlParameters)
    {
        $this->lex = new CommandLexer();
        if(!$this->lex->init($fileName))
        {
            throw new CanNotOpenCommandsFileException($fileName);
        }
        // Obtener token inicial
        $this->tok = $this->next();

        //
        // Parser el archivo
        //
        while($this->tok->id() != CommandToken::TOK_EOF)
        {
            if($this->tok->id() == '/')
            {
                if(!$this->checkURL($urlParts, $urlParameters))
                {
                    $this->lex->skipLine();
                }
                else
                {
                    //
                    // Obtener el nombre del comando
                    //
                    $commandName = $this->getCommandName();

                    //
                    // Obtener los métodos disponibles para el comando
                    //
                    $commandMethods = $this->getCommandMethods();

                    //
                    // Comprobar que el método de solicitud está dentro
                    // de la lista de métodos disponibles para este comando
                    //
                    if(!in_array($method, $commandMethods))
                    {
                        throw new MethodNotAllowedException();
                    }

                    // Cerrar en analizador léxico
                    $this->lex->close();

                    // Crear comando
                    $command = new $commandName();

                    //
                    // Check is a valid command
                    //
                    if(!($command instanceof Command))
                    {
                        throw new InvalidCommandException();
                    }
                    return $command;
                }
            }
            else
            {
                $this->next();
            }
        }
        // Cerrar en analizador léxico
        $this->lex->close();

        throw new InvalidEndpointException();
    }

    /**
     * Quote string
     * 
     * @param string $s
     * 
     * @return string
     */
    private function quote($s)
    {
        if($s == CommandToken::TOK_EOF)
            return '<EOF>';
        if($s == CommandToken::TOK_LEXEME)
            return 'lexeme';
        return '"' . $s . '"';
    }

    /**
     * Obtener el siguiente token
     *
     * @return CommandToken
     */
    private function next()
    {
        $this->tok = $this->lex->getToken();

        return $this->tok;
    }

    /**
     * Comprobar que el token actual es el token esperado, y pasa al siguiente
     *
     * @param string $t
     *
     * @return CommandToken
     *
     * @throws UnexpectedTokenException
     */
    private function match($t)
    {
        if($this->tok->id() == $t)
        {
            return $this->next();
        }
        throw new UnexpectedTokenException($this->quote($t), $this->quote($this->tok->id()));
    }

    /**
     * Comprobar si la URL actual en el archivo de configuración es la misma a la que se obtuvo en la solicitud
     *
     * @param array $urlParts      Arreglo con conforma las partes de la url
     * @param array $urlParameters Arreglo donde se almacenarán los argumentos en la URL
     *
     * @return boolean true|false
     *
     * @throws UnexpectedTokenException
     */
    private function checkURL($urlParts, &$urlParameters)
    {
        $tok = $this->tok;

        // ...
        $partIndex = 0;
        $partsCounts = count($urlParts);
        $urlParameters = array();

        if($partsCounts == 0)
        {
            // Saltear /
            $tok = $this->match('/');
            if($tok->id() != '|')
            {
                throw new UnexpectedTokenException($this->quote('|'), $this->quote($tok->id()));
            }
            return true;
        }

        while($tok->id() != '|' && $tok->id() != CommandToken::TOK_EOF)
        {
            // Saltear /
            $tok = $this->match('/');

            // Obtener la parte actual de la URL
            $part = $urlParts[$partIndex];

            // ...
            if($tok->id() == '{')
            {
                $tok = $this->next();
                if($tok->id() != CommandToken::TOK_LEXEME)
                {
                    throw new UnexpectedTokenException('lexeme', $this->quote($tok->id()));
                }
                $urlParameters[$tok->lexeme()] = $part;
                $tok = $this->next();
                $tok = $this->match('}');
            }
            else if($tok->id() == '|')
            {
                break;
            }
            else
            {
                if($tok->lexeme() != $part)
                {
                    return false;
                }
                $tok = $this->next();
            }
            // Abanzar el índice de la parte actual
            $partIndex++;
            // Si el índice de la parte actual ha llegado a la cantidad de partes de la URL cortar el bucle
            if($partIndex == count($urlParts))
            {
                break;
            }
        }
        if($partIndex == count($urlParts) && $tok->id() == '|')
        {
            return true;
        }
        return false;
    }

    /**
     * Obtener el nombre de la clase del comando
     *
     * @return string Nombre del comando
     *
     * @throws UnexpectedTokenException
     */
    private function getCommandName()
    {
        $tok = $this->match('|');
        // ...
        if($tok->id() != CommandToken::TOK_LEXEME)
        {
            throw new UnexpectedTokenException('lexeme', $this->quote($tok->id()));
        }
        $name = $tok->lexeme();
        $tok = $this->next();
        while($tok->id() == '\\')
        {
            $name .= '\\';
            $tok = $this->next();
            if($tok->id() != CommandToken::TOK_LEXEME)
            {
                throw new UnexpectedTokenException('lexeme', $this->quote($tok->id()));
            }
            $name .= $tok->lexeme();
            $tok = $this->next();
        }
        return $name;
    }

    /**
     * Obtener la lista de métodos disponibles para el comando
     *
     * @return array Arreglo de los nombres de los métodos
     *
     * @throws UnexpectedTokenException
     */
    private function getCommandMethods()
    {
        $tok = $this->match('|');

        // Arreglo de los métodos
        $methods = array();

        // Almacenar la línea actual
        $currentLine = $tok->line();

        //
        // Leer mientras no se llege al final del archivo o hasta que pase a la siguiente línea
        //
        while($tok->id() != CommandToken::TOK_EOF && $tok->line() == $currentLine)
        {
            if($tok->id() == ',')
            {
                $tok = $this->next();
            }
            else if($tok->id() != CommandToken::TOK_LEXEME)
            {
                throw new UnexpectedTokenException('lexeme', $this->quote($tok->id()));
            }
            array_push($methods, $tok->lexeme());
            $tok = $this->next();
        }
        // ...
        return $methods;
    }
}