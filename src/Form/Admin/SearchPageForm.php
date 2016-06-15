<?php

/*
 * Copyright BibLibre, 2016
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software.  You can use, modify and/ or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software's author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user's attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software's suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */

namespace Search\Form\Admin;

use Zend\Form\Form;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;

class SearchPageForm extends Form
{
    use TranslatorAwareTrait;

    protected $apiManager;
    protected $searchFormManager;

    public function init()
    {
        $translator = $this->getTranslator();

        $this->add([
            'name' => 'o:name',
            'type' => 'Text',
            'options' => [
                'label' => $translator->translate('Name'),
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'o:path',
            'type' => 'Text',
            'options' => [
                'label' => $translator->translate('Path'),
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'o:index_id',
            'type' => 'Select',
            'options' => [
                'label' => $translator->translate('Index'),
                'value_options' => $this->getIndexesOptions(),
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'o:form',
            'type' => 'Select',
            'options' => [
                'label' => $translator->translate('Form'),
                'value_options' => $this->getFormsOptions(),
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
    }

    public function setApiManager($apiManager)
    {
        $this->apiManager = $apiManager;
    }

    public function getApiManager()
    {
        return $this->apiManager;
    }

    public function setSearchFormManager($searchFormManager)
    {
        $this->searchFormManager = $searchFormManager;
    }

    public function getSearchFormManager()
    {
        return $this->searchFormManager;
    }

    protected function getIndexesOptions()
    {
        $api = $this->getApiManager();

        $indexes = $api->search('search_indexes')->getContent();
        $options = [
            '' => $this->getTranslator()->translate('None'),
        ];
        foreach ($indexes as $index) {
            $options[$index->id()] =
                sprintf('%s (%s)', $index->name(), $index->adapterLabel());
        }

        return $options;
    }

    protected function getFormsOptions()
    {
        $formManager = $this->getSearchFormManager();
        $forms = $formManager->getAll();

        $options = [
            '' => $this->getTranslator()->translate('None'),
        ];
        foreach ($forms as $name => $form) {
            $options[$name] = $name;
        }

        return $options;
    }
}
