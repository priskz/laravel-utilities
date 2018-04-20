<?php namespace Priskz\LaravelUtilities\Alert;

use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;

class Alert
{
	/**
	 * @var Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * @var Illuminate\Support\MessageBag
	 */
	protected $bag;

	/**
	 * @var string The wrapper for each alert key.
	 */
	protected $wrapper = '<div class="alert alert-:type"><button id="alert-close" type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:messages</div>';

	/**
	 * @var string The wrapper for each individual message.
	 */
	protected $format = '<div class="message">:message</div>';

	/**
	 * You must construct additional classes!
	 */
	public function __construct(Store $session, $sessionKey = 'laravel-utilities.alerts')
	{
		$this->session = $session;
		$this->sessionKey = $sessionKey;

		$this->createBag();

		$this->bag->setFormat($this->format);
	}

	/**
	 * Create the message bag and import any session messages.
	 *
	 * @return void
	 */
	protected function createBag()
	{
		$this->bag = new MessageBag;

		// Are there any default errors, from a form validator for example?
		if ($this->session->has('errors'))
		{
			$errors = $this->session->get('errors')->all(':message');

			$this->bag->merge(['error' => $errors]);
		}

		// Do we have any flashed messages already?
		if ($this->session->has($this->sessionKey))
		{
			$this->bag->merge(json_decode($this->session->get($this->sessionKey), true));
		}
	}

	/**
	 * Magic caller to allow any message key the user wants.
	 *
	 * @param  string  $key
	 * @param  array   $params
	 * @return void
	 */
	public function __call($key, $params)
	{
		$string = call_user_func_array('sprintf', $params);

		$this->bag->add(strtolower($key), $string);

		$this->flash();
	}

	/**
	 * Show one, some, or all alert groups.
	 *
	 * @param  string|array  $groups
	 * @return string
	 */
	public function show($groups = null)
	{
		// Load existing keys in our message bag.
		$keys = array_keys($this->bag->getMessages());

		// Do we only want to show specific groups?
		if ( ! is_null($groups))
		{
			if (is_string($groups)) $groups = func_get_args();

			$keys = array_intersect($groups, $keys);
		}

		$output = '';

		foreach ($keys as $key)
		{
			$output .= $this->render($key);
		}

		return $output;
	}

	/**
	 * Render the supplied message group.
	 *
	 * @param  string  $key
	 * @return string
	 */
	public function render($key)
	{
		if ( ! $this->bag->has($key)) return;

		return str_replace(
			[':type', ':messages'],
			[$key, implode(PHP_EOL, $this->bag->get($key))],
			$this->wrapper
		) . PHP_EOL;
	}

	/**
	 * Get the message bag.
	 *
	 * @return Illuminate\Support\MessageBag
	 */
	public function getBag()
	{
		return $this->bag;
	}

	/**
	 * Flash all current messages to the session.
	 *
	 * @return void
	 */
	protected function flash()
	{
		$this->session->flash($this->sessionKey, $this->bag->toJson());
	}
}