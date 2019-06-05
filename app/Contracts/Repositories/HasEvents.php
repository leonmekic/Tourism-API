<?php
namespace App\Contracts\Repositories;

use Illuminate\Contracts\Events\Dispatcher;

/**
 * Add repository events ability
 */
trait HasEvents
{
    /**
     * EventListener instance
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * List of allowed event actions
     *
     * @var array
     */
    protected $allowedEventActions = [
        'create#before',
        'create#after',
        'update#before',
        'update#after',
        'delete#before',
        'delete#after',
    ];

    /**
     * Key-Value list of events which will be replaced
     *
     * @var array
     */
    protected $replacedEventActions = [];

    /**
     * Shoul events be dispatched
     *
     * @var boolean
     */
    protected $mute_events = false;

    /**
     * Subscribe to some custom Repository event
     *
     * @param string $action
     * @param mix $callable
     * @param integer $order
     *
     * @return void
     */
    public static function addAction($action, $callable, $order = 10)
    {
        if (!$dispatcher = self::getEventDispatcher()) {
            return false;
        }

        $class = get_called_class();

        $instance = app()->make($class);

        $event = self::makeEventString($instance->getModelClass(), $action);

        // You can send array of class name and method name
        if (is_array($callable)) {
            $callable = implode("@", $callable);
        }

        $dispatcher->listen($event, $callable);
    }

    /**
     * Generate event string for repository events
     *
     * @param string|object $class
     * @param string $action
     *
     * @return string
     */
    public static function makeEventString($class, $action)
    {
        $event = class_to_event_str($class, $action);

        $event = 'REPOSITORY_'.$event;

        return $event;
    }

    public static function getEventForAction($action)
    {
        $instance = app()->make(static::class);

        return self::makeEventString($instance->getModelClass(), $action);
    }

    public function makeEventForAction($action)
    {
        return self::makeEventString($this->getModelClass(), $action);
    }

    /**
     * Trigger repository event.
     * Crete event name depence on model class name and action
     *
     * @param string $action
     * @param array $data
     *
     * @return $this
     */
    protected function trigger($action, array $data = [])
    {
        if ($this->mute_events) {
            return $this;
        }

        // Allow to replace some predefined event with some other
        if (!empty($this->replacedEventActions[$action])) {
            $action = $this->replacedEventActions[$action];
        }

        // Create event name from class name and action
        $event = $this->makeEventForAction($action);

        if (!count($data)) {
            // Trigger without params
            return event($event);
        }

        // Trigger with params
        return event($event, $data);
    }

    /**
     * Get EventDispatcher
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return app()['events'];
    }

    /**
     * Push new allowed actions
     *
     * @param array $actions
     *
     * @return void
     */
    public function addEventActions($actions)
    {
        if (!is_array($actions)) {
            return $this->addEventActions([$actions]);
        }

        $this->allowedEventActions = array_merge($this->allowedEventActions, $actions);
    }

    /**
     * Replace event actions with some other event
     *
     * @param array $actions - key-value
     *
     * @return void
     */
    protected function replaceEventActions(array $actions)
    {
        foreach ($actions as $key => $action) {
            $this->replaceEventAction($key, $action);
        }
    }

    /**
     * Replace some predefined action string with other
     *
     * @param string $replacer
     * @param string $replacement
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function replaceEventAction($replacer, $replacement)
    {
        if (!in_array($replacer, $this->allowedEventActions)) {
            throw new \Exception("Action \"{$replacer}\" does not exists");
        }

        $this->replacedEventActions[$replacer] = $replacement;
    }

    /**
     * Return list of allowed event actions
     *
     * @return array
     */
    public function getEventActions()
    {
        return $this->allowedEventActions;
    }

    /**
     * Turn off events
     *
     * @param boolean $mute
    */
    public function muteEvents($mute = true)
    {
        $this->mute_events = $mute;
    }
}
