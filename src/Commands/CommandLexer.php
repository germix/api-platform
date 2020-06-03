<?php

namespace Germix\Api\Commands;

/**
 * Analizador léxico del archivo donde se configuran los comandos asociados a la URL
 *
 * @author Germán Martínez
 * 
 */
class CommandLexer
{
    private $in;                    // Archivo de análisis
    private $line = 0;              // Línea actual durante el análisis
    private $cache = null;          // Caracter de caché

    /**
     * Abrir el archivo para el análisis léxico
     * 
     * @param string $fname Nombre de archivo
     * 
     * @return boolean true|false
     */
    public function init($fname)
    {
        $this->in = null;
        if(file_exists($fname))
        {
            $this->in = fopen($fname, "r");
        }
        return ($this->in != null);
    }

    /**
     * Cerrar el archivo para el análisis léxico
     */
    public function close()
    {
        fclose($this->in);
    }

    /**
     * Obtener el siguiente token
     *
     * @return CommandToken Token
     */
    public function getToken()
    {
        while(true)
        {
            $c = $this->read();
            if($c == '')
            {
                return new CommandToken(CommandToken::TOK_EOF, $this->line, null);
            }
            if($c == "\n")
            {
                $this->line++;
            }
            else if($c == "\r")
            {
                // NADA
            }
            else if(ctype_space($c))
            {
                // NADA
            }
            else if(ctype_alnum($c))
            {
                // Guardar el primer caracter
                $s = $c;

                // Leer todos los caracteres válidos de un lexema de identificador (alfanumérico y '_')
                while(true)
                {
                    $c = $this->read();
                    if(!(!$this->feof() && (ctype_alnum($c) || $c == '_' || $c == '-')))
                    {
                        break;
                    }
                    $s .= $c;
                }

                // Si no se ha llegado al final, poner en cache el último caracter
                if(!$this->feof())
                {
                    $this->save($c);
                }

                return new CommandToken(CommandToken::TOK_LEXEME, $this->line, $s);
            }
            else if($c == '#')
            {
                // Saltear comentario
                $this->skipLine();
            }
            else
            {
                return new CommandToken($c, $this->line, null);
            }
        }
        return null;
    }

    /**
     * Saltar a la siguiente línea
     */
    public function skipLine()
    {
        while(true)
        {
            $c = $this->read();
            if($this->feof() || $c == "\n")
            {
                if($c == "\n")
                {
                    $this->line++;
                }
                break;
            }
        }
    }

    /**
     * Comprobar que se ha llegado al fin del archivo
     *
     * @return boolean true|false
     */
    private function feof()
    {
        return feof($this->in);
    }

    /**
     * Leer el siguiente caracter en el archivo
     *
     * @return string Caracter
     */
    private function read()
    {
        if($this->cache == null)
        {
            return fgetc($this->in);
        }
        $c = $this->cache;
        $this->cache = null;
        return $c;
    }

    /**
     * Guardar un caracter en la caché
     *
     * @param string $c Caracter
     */
    private function save($c)
    {
        $this->cache = $c;
    }
};

