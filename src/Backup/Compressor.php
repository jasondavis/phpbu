<?php
namespace phpbu\Backup;

use phpbu\App\Exception;

/**
 * Compressor
 *
 * @package    phpbu
 * @subpackage Backup
 * @author     Sebastian Feldmann <sebastian@phpbu.de>
 * @copyright  Sebastian Feldmann <sebastian@phpbu.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://phpbu.de/
 * @since      Class available since Release 1.0.0
 */
class Compressor
{
    /**
     * Path to command binary.
     *
     * @var string
     */
    private $path;

    /**
     * Command name.
     *
     * @var string
     */
    private $cmd;

    /**
     * Suffix for compressed files.
     *
     * @var string
     */
    private $suffix;

    /**
     * Constructor.
     *
     * @param string $cmd
     * @param string $suffix
     * @param string $pathToCmd without trailing slash
     */
    protected function __construct($cmd, $suffix, $pathToCmd = null)
    {
        $this->path   = $pathToCmd . (!empty($pathToCmd) ? DIRECTORY_SEPARATOR : '');
        $this->cmd    = $cmd;
        $this->suffix = $suffix;
    }

    /**
     * Return the cli command.
     *
     * @param  boolean $includingPath
     * @return string
     */
    public function getCommand($includingPath = true)
    {
        return ($includingPath ? $this->path : '') . $this->cmd;
    }

    /**
     * Returns the compressor suffix e.g. 'bzip2'
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Factory method.
     *
     * @param  string $name
     * @throws \phpbu\App\Exception
     * @return \phpbu\Backup\Compressor
     */
    public static function create($name)
    {
        $path = null;
        // check if a path is given for the compressor
        if (basename($name) !== $name) {
            $path = dirname($name);
            $name = basename($name);
        }

        $availableCompressors = array(
            'gzip' => array(
                'gzip',
                'gz'
            ),
            'bzip2' => array(
                'bzip2',
                'bz2'
            ),
            'zip' => array(
                'zip',
                'zip'
            )
        );
        if (!isset($availableCompressors[$name])) {
            throw new Exception('invalid compressor:' . $name);
        }
        return new static($availableCompressors[$name][0], $availableCompressors[$name][1], $path);
    }
}
