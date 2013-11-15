<?php 
namespace mindofmicah\Makers\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Pluralizer;

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
            $config =  new stdClass;
        } else {
            $config = json_decode($this->file->get(__DIR__.'/../../../../config.json'));
        }

        if (!$config->namespace) {
            $config->namespace = $this->ask('Hey, which namespace?');
        }

        $directory = app_path() . '/'.strtr($config->namespace, '\\','/');
        if (!$this->file->isDirectory($directory)) {
            $this->file->makeDirectory($directory, 0775, true);
        }

        if (!$this->file->exists($directory . '/Validator.php')) {
            $contents = str_replace('$namespace',$config->namespace,$this->file->get(__DIR__.'/../templates/Validator.txt'));
            $this->file->put(
                $directory . '/Validator.php', 
                $contents);
        }

        $model = ucfirst($this->argument('model'));
        $validator_name = $model . 'Validator';
        $rules = "";

        
        $fields = $this->getTableInfo($model);
        foreach($fields  as $field_name => $field) {
            if (in_array($field_name, array('password','id','created_at','updated_at','deleted_at'))) {
                continue;
            }
            $r = array();
            if ($field->getNotNull()) {
                $r[] = 'required';
            } 

            while ($r[] = $this->ask('Add rule for '.$field_name.(isset($r[0]) ? ' besides ('.implode(', ',$r).')' :''). '? [Press enter for no]'));           
            array_pop($r);
            if (isset($r[0])) {
                $rules.= "\n\t\t'{$field_name}' => '".implode('|', $r). "',";
            }
        }

        $contents = str_replace(array('$namespace','$model', '$rules_string'), array($config->namespace, $validator_name, $rules), $this->file->get(__DIR__.'/../templates/sub-validator.txt'));
        $this->file->put($directory . '/'. $validator_name . '.php', $contents);

        // If there isn't a config file, we need to build one
        // Set a flag that we'll need to save it
        // If there is one, use it

        //

        $this->info("File created at " . $directory . '/'. $validator_name . '.php');

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
            array('model', InputArgument::REQUIRED, 'Name of the model to generate.'),
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
    public function getTableInfo($model)
    {
        $table = strtolower(Pluralizer::plural($model));
        return \DB::getDoctrineSchemaManager()->listTableDetails($table)->getColumns();
    }
    protected function getModelAttributes($table)
    {
        $names = array_keys($table);

        return array_diff($names, array('id', 'created_at', 'updated_at', 'deleted_at', 'password'));
    }


}
