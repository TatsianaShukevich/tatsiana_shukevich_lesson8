<?php
/**
 * @file
 * Contains \Drupal\tatsiana_shukevich_lesson8\Form\LogMessageForm.
 */

namespace Drupal\tatsiana_shukevich_lesson8\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\tatsiana_shukevich_lesson8\LogService;

/**
 * Form with field for entering message and with 3 buttons for logging and cache.
 */
class LogMessageForm extends FormBase {

    /**
     * The service for logging.
     *
     * @var \Drupal\tatsiana_shukevich_lesson8\LogService
     */
    protected $logService;

    /**
     * The cache.default cache backend.
     *
     * @var \Drupal\Core\Cache\CacheBackendInterface
     */
    protected $cacheBackend;

    /**
     * Dependency injection through the constructor.
     *
     * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
     *   The cache object associated with the default bin.
     */
    public function __construct(CacheBackendInterface $cache_backend, LogService $logService) {
        $this->cacheBackend = $cache_backend;
        $this->logService = $logService;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('cache.default'),
            $container->get('tatsiana_shukevich_lesson8.log_service')
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'log_message_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#attributes'] = array('novalidate' => TRUE);
        $form['message'] = array(
            '#type' => 'textfield',
            '#size' => 300,
            '#title' => $this->t('Type a message'),
            '#required' => TRUE,
        );
        $form['log_and_cache'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save message in log & cache'),
            '#submit' => array('::handleSaveLogCache'),
            '#name' => 'log_and_cache',
        );
        $form['invalidate_cache'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Invalidate cache'),
            '#submit' => array('::handleInvalidateCache'),
            '#name' => 'invalidate_cache',
            '#limit_validation_errors' => array(),

        );
        $form['delete_cache'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Delete cache'),
            '#submit' => array('::handleDeleteCache'),
            '#name' => 'delete_cache',
            '#limit_validation_errors' => array(),
        );

        return $form;
    }

    /**
     * {@inheritdoc}.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        parent::submitForm($form, $form_state);
    }

    /**
     * Submit handler to save message in log and cache.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function handleSaveLogCache(array &$form, FormStateInterface $form_state) {
        $message = $form_state->getValue('message');
        $this->cacheBackend->set('tatsiana_shukevich_lesson8_message', $message, CacheBackendInterface::CACHE_PERMANENT);
        //logging
        $this->logService->logMessageToChannels($message);
    }
    
    /**
     * Submit handler to invalidate cache.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function handleInvalidateCache(array &$form, FormStateInterface $form_state) {
        $this->cacheBackend->invalidate('tatsiana_shukevich_lesson8_message');
    }

    /**
     * Submit handler to delete cache.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function handleDeleteCache(array &$form, FormStateInterface $form_state) {
        $this->cacheBackend->delete('tatsiana_shukevich_lesson8_message');
    }

}