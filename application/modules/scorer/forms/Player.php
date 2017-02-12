<?php
/**
 * Manage player
 *
 * @author Dean Blackborough
 * @copyright G3D Development Limited
 */
class Scorer_Form_Player extends Zend_Form
{
    /**
     * Form elements
     *
     * @var array
     */
    protected $elements = array();

    /**
     * Pass in any values that are needed to set up the form, optional
     *
     * @param array|null Options for form
     */
    public function __construct($id = null, $options = null)
    {
        parent::__construct($options = null);

        if ($id === null) {
            $this->setAction('/scorer/player/add');
        } else {
            $this->setAction('/scorer/player/edit/player/' . $id);
        }

        $this->setUpFormElements();
        $this->validationRules();
        $this->addElementsToForm('player', 'Player details', $this->elements);
        $this->addElementDecorators();
    }

    /**
     * Set up the form elements needed for this form
     *
     * @return void
     */
    protected function setUpFormElements()
    {
        $forename = new Zend_Form_Element_Text('forename');
        $forename->setLabel('Forename (required)');
        $forename->setDescription('Enter player forename');
        $forename->setAttribs(array('maxlength'=>255, 'class'=>'form-control input-sm'));

        $this->elements['forename'] = $forename;

        $surname = new Zend_Form_Element_Text('surname');
        $surname->setLabel('Surname (required)');
        $surname->setDescription('Enter player surname');
        $surname->setAttribs(array('maxlength'=>255, 'class'=>'form-control input-sm'));

        $this->elements['surname'] = $surname;

        $contact_no = new Zend_Form_Element_Text('contact_no');
        $contact_no->setLabel('Contact number');
        $contact_no->setDescription('Enter player contact number');
        $contact_no->setAttribs(array('maxlength'=>20, 'class'=>'form-control input-sm'));

        $this->elements['contact_no'] = $contact_no;

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email');
        $email->setDescription('Enter player email');
        $email->setAttribs(array('maxlength'=>255, 'class'=>'form-control input-sm'));

        $this->elements['email'] = $email;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->setAttribs(array('class'=>'btn btn-primary'));

        $this->elements['submit'] = $submit;
    }

    /**
     * Add any validation rules to the form elements
     *
     * @return void
     */
    protected function validationRules()
    {
        $this->elements['forename']->setRequired();
        $this->elements['surname']->setRequired();
        $this->elements['email']->setRequired();
        $this->elements['email']->addValidator(new Zend_Validate_EmailAddress());
    }

    /**
     * Add our form decorators
     *
     * @return void
     */
    protected function addElementDecorators()
    {
        $this->setDecorators(array('FormElements',array('Form', array('class'=>'form'))));

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Description', array('tag' => 'p', 'class'=>'help-block')),
            array('Errors', array('class'=> 'alert alert-danger')),
            array('Label'),
            array('HtmlTag', array(
                'tag' => 'div',
                'class'=> array(
                    'callback' => function($decorator) {
                        if($decorator->getElement()->hasErrors()) {
                            return 'form-group has-error';
                        } else {
                            return 'form-group';
                        }
                    })
            ))
        ));

        $this->elements['submit']->setDecorators(array(array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class'=>'form-group'))));
    }

    /**
     * Add the elements to the form, create the fieldset
     *
     * @param string $id
     * @param string $label Label for the field set
     * @param array $elements Elements to add to form and pass to display group
     *
     * @return void
     */
    protected function addElementsToForm($id, $label, array $elements)
    {
        $this->addElements($elements);

        $this->addDisplayGroup($elements, $id,
            array('legend'=>$label, 'escape'=>false));
    }
}
