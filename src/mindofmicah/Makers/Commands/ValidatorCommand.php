<?php 
namespace mindofmicah\Makers\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ValidatorCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'wd:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build a validator service.';

    protected $file;
    public function __construct(File $file) 
    {
        $this->file = $file;
        parent::__construct();
    }    
    
    public function fire()
    {
        if (!$this->file->exists(__DIR__ . '/../../../../config.json') ) {
            $config = [];
        } else {
            $config = json_decode($this->file->get(__DIR__.'/config.json'));
        }

        if (!array_key_exists('namespace', $config)) {
            $config['namespace'] = $this->ask('Hey, which namespace?');
        }

        $directory = app_path() . '/'.strtr($config['namespace'], '\\','/');
        if (!$this->file->isDirectory($directory)) {
            $this->file->makeDirectory($directory, 0775, true);
        }

        if (!$this->file->exists($directory . '/Validator.php')) {
            $contents = str_replace('$namespace',$config['namespace'],$this->file->get(__DIR__.'/../Validator.txt'));
            $this->file->put(
                $directory . '/Validator.php', 
                $contents);
        }

        // If there isn't a config file, we need to build one
        // Set a flag that we'll need to save it
        // If there is one, use it

        //



        $this->file->put(__DIR__.'/../../../../config.json', json_encode($config));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
/*            array('name', InputArgument::REQUIRED, 'Name of the controller to generate.'),*/
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

}
