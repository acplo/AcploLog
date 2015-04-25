<?php
/**
 * Trait for loggable objects
 *
 * @package   AcploLog\Log
 * @author    Abel Lopes <abel@abellpes.eti.br>
* @link      http://www.abellpes.eti.br Development Blog
 * @link      http://github.com/acplo/AcploLog for the canonical source repository
 * @copyright Copyright (c) 2015-2020 Abel Lopes (http://www.abellpes.eti.br)
 * @license   http://www.abellpes.eti.br/licenca-bsd New BSD license
 */
namespace AcploLog\Log;

/**
 * Trait for loggable objects
 *
 * @package   AcploLog\Log
 * @author    Abel Lopes <abel@abellpes.eti.br>
* @link      http://www.abellpes.eti.br Development Blog
 * @link      http://github.com/acplo/AcploLog for the canonical source repository
 * @copyright Copyright (c) 2015-2020 Abel Lopes (http://www.abellpes.eti.br)
 * @license   http://www.abellpes.eti.br/licenca-bsd New BSD license
 */
trait Loggable
{
    /**
     * Function to collect properties values
     *
     * @return array Array contendo as propriedades do object e seus valores
     */
    public function acploLogMe()
    {
        $ret = [];
        $ret[get_class($this)] = [];
        foreach (get_object_vars($this) as $name => $content) {
            if (!is_object($content)) {
                $ret[$name] = ['type' => gettype($content), 'content' => $content];
            } else {
                $ret[$name] = ['type' => gettype($content), 'class' => get_class($content)];
            }
        }

        return $ret;
    }
}
