<?php namespace Illuminate\Console;

use Illuminate\Container;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Application extends \Symfony\Component\Console\Application {

	/**
	 * The Laravel application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $laravel;

	/**
	 * Start a new Console application.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return Illuminate\Console\Application
	 */
	public static function start($app)
	{
		return require __DIR__.'/../../start.php';
	}

	/**
	 * Add a command to the console.
	 *
	 * @param  Symfony\Component\Console\Command\Command  $command
	 * @return Symfony\Component\Console\Command\Command
	 */
	public function add(SymfonyCommand $command)
	{
		if ($command instanceof Command)
		{
			$command->setLaravel($this->laravel);
		}

		return $this->addToParent($command);
	}

	/**
	 * Add the command to the parent instance.
	 *
	 * @param  Symfony\Component\Console\Command  $command
	 * @return Symfony\Component\Console\Command
	 */
	protected function addToParent($command)
	{
		return parent::add($command);
	}

	/**
	 * Add a command, resolving through the application.
	 *
	 * @param  string  $command
	 * @return void
	 */
	public function resolve($command)
	{
		return $this->add($this->laravel[$command]);
	}

	/**
	 * Resolve an array of commands through the application.
	 *
	 * @param  array|dynamic  $commands
	 * @return void
	 */
	public function resolveCommands($commands)
	{
		$commands = is_array($commands) ? $commands : func_get_args();

		foreach ($commands as $command)
		{
			$this->resolve($command);
		}
	}

	/**
	 * Set the Laravel application instance.
	 *
	 * @param  Illuminate\Foundation\Application  $laravel
	 * @return void
	 */
	public function setLaravel($laravel)
	{
		$this->laravel = $laravel;
	}

}