<?php 
namespace mindofmicah\Makers\Commands;


use Illuminate\Console\Command;
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
