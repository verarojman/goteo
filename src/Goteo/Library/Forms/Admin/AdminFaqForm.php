<?php

/*
 * This file is part of the Goteo Package.
 *
 * (c) Platoniq y Fundación Goteo <fundacion@goteo.org>
 *
 * For the full copyright and license information, please view the README.md
 * and LICENSE files that was distributed with this source code.
 */

namespace Goteo\Library\Forms\Admin;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\FormInterface;
use Goteo\Library\Forms\AbstractFormProcessor;
use Symfony\Component\Validator\Constraints;
use Goteo\Library\Text;
use Goteo\Library\Forms\FormModelException;

use Goteo\Model\Faq;
use Goteo\Model\Faq\FaqSubsection;
use Goteo\Application\Config;

class AdminFaqForm extends AbstractFormProcessor {

    public function getConstraints($field) {
        return [new Constraints\NotBlank()];
    }

    public function createForm() {
        $model = $this->getModel();

        $builder = $this->getBuilder();
        $options = $builder->getOptions();
        $defaults = $options['data'];

        $subsections = [];
        foreach(FaqSubsection::getList() as $s) {
            $subsections[$s->id] = $s->name;
        }

        $builder
            ->add('title', 'text', [
                'disabled' => $this->getReadonly(),
                'required' => true,
                'constraints' => $this->getConstraints('name'),
                'label' => 'regular-title'
            ])
            ->add('description', 'markdown', [
                'disabled' => $this->getReadonly(),
                'required' => true,
                'label' => 'regular-description',
                'attr' => [
                    'data-image-upload' => '/api/blog/images',
                    'help' => Text::get('tooltip-drag-and-drop-images')
                ]
            ])
            ->add('subsection_id', 'choice', [
                'disabled' => $this->getReadonly(),
                'required' => true,
                'label' => 'regular-subsection',
                'choices' => $subsections
            ])
            ->add('pending', 'boolean', array(
                'label' => 'admin-faq-pending',
                'required' => false
            ))
            ->add('submit', 'submit', [
                'label' => 'regular-submit',
                'attr' => ['class' => 'btn btn-cyan'],
                'icon_class' => 'fa fa-save'
            ])
            ;

        return $this;
    }


    public function save(FormInterface $form = null, $force_save = false) {
        if(!$form) $form = $this->getBuilder()->getForm();
        if(!$form->isValid() && !$force_save) throw new FormModelException(Text::get('form-has-errors'));

        $data = $form->getData();
        $model = $this->getModel();
        $model->rebuildData($data, array_keys($form->all()));
        $model->node = Config::get('node');
        if ($data['pendign']) {
          $model->setPending($model->id, 'post');
        }
        $model->order = Faq::getList([], 0, 0, true) + 1;

        $errors = [];
        if (!$model->save($errors)) {
            throw new FormModelException(Text::get('form-sent-error', implode(', ',$errors)));
        }
        if(!$form->isValid()) throw new FormModelException(Text::get('form-has-errors'));

        return $this;
    }
}