<?php
/**
 * @file
 * Contains \Drupal\tatsiana_shukevich_lesson8\Controller\LogMessageController.
 */

namespace Drupal\tatsiana_shukevich_lesson8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Controller routines for magic-ball routes.
 */
class LogMessageController extends ControllerBase {

    /**
     * The form builder.
     *
     * @var \Drupal\Core\Form\FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * The cache.default cache backend.
     *
     * @var \Drupal\Core\Cache\CacheBackendInterface
     */
    protected $cacheBackend;

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('form_builder'),
            $container->get('cache.default')
        );
    }

    /**
     * Constructs an LogMessageController object.
     *
     * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
     *   The form builder.
     * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
     *   The cache object associated with the default bin.
     */
    public function __construct(FormBuilderInterface $form_builder, CacheBackendInterface $cache_backend) {
        $this->formBuilder = $form_builder;
        $this->cacheBackend = $cache_backend;
    }

    /**
     * Shows page with LogMessageForm.
     *
     * @return array
     */
    public function showLogMessagePage() {
        $item = $this->cacheBackend->get('tatsiana_shukevich_lesson8_message', TRUE);
        if ($item == FALSE) {
            $item_status = $this->t('There are no any items in cache');
        }
        else {
            $item_status = $item->valid ? $this->t('Cache item: %data - valid', array('%data' => $item->data)) :
                $this->t('Cache item: %data - invalid', array('%data' => $item->data));            
        }
        drupal_set_message($item_status);
        $form = $this->formBuilder->getForm('\Drupal\tatsiana_shukevich_lesson8\Form\LogMessageForm');

        return array(           
                'form' => $form,
             );
    }
}
