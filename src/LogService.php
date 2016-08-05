<?php
/**
 * @file
 * Contains \Drupal\tatsiana_shukevich_lesson8\LogService.
 */

namespace Drupal\tatsiana_shukevich_lesson8;

/**
 * Saves message in log.
 */
class LogService {

    /**
     * The logger.factory service.
     *
     * @var \Drupal\Core\Logger\LoggerChannelFactory
     */
    protected $loggerFactory;

    /**
     * Constructs a LogService object.
     * 
     * @param \Drupal\Core\Logger\LoggerChannelFactory $factory
     */
    public function __construct($factory) {
        $this->loggerFactory = $factory;
    }

    /**
     * Logs message to channels.
     * 
     * @param string $message
     */
    public function logMessageToChannels($message) {
        $this->loggerFactory->get('tatsiana_shukevich_lesson8')->emergency($message);
        $this->loggerFactory->get('tatsiana_shukevich_lesson8')->warning($message);
    }
}