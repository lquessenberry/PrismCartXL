<?php

namespace Drupal\commerce_demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\devel_generate\Plugin\DevelGenerateBase;
use Drupal\commerce_demo\Plugin\DevelGenerate\CommerceDemoGenerator;

/**
 * Form for generating commerce demo content.
 */
class CommerceDemoGenerateForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'commerce_demo_generate_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Generate demo content for Commerce. This will create sample products, orders, and customers.'),
    ];

    $form['product_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Products'),
      '#default_value' => 50,
      '#min' => 1,
      '#max' => 100,
    ];

    $form['customer_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Customers'),
      '#default_value' => 10,
      '#min' => 1,
      '#max' => 50,
    ];

    $form['order_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Orders'),
      '#default_value' => 20,
      '#min' => 1,
      '#max' => 100,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Content'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $generator = \Drupal::service('plugin.manager.devel_generate')->createInstance('commerce_demo');
    
    $generator->generate($form_state->getValue('product_count'));
    
    $this->messenger()->addMessage($this->t('Commerce demo content has been generated successfully.'));
    
    $form_state->setRedirect('commerce_demo.demo');
  }
}
