<?php

class ErrorController extends Zend_Controller_Action
{
    private $msg;

    public function init(){

         $this->msg = $this->_helper->getHelper('FlashMessenger');
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        $this->view->headTitle("Napaka");
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Ta stran (še) ne obstaja!';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                switch($errors->exception->getCode()){
                    #LOGIN:
                    case 10:
                        #FIELDS EMPTY:
                        $this->msg->addMessage(array('Zaupati mi moraš tako email kot geslo, ker drugače ne morem preveriti tvoje identitete!', 'error'));
                        $this->_redirect('/');
                        //$this->view->message = 'Zaupati mi moraš tako email kot geslo, drugače ne morem preveriti tvojih podatkov!';
                        break;
                    case 11:
                        #CREDENTIALS FAILED:
                        $this->msg->addMessage(array('Hm... Ni se mi uspelo prepričat, da si zares to kar praviš da si. Prosim preveri, če si pravilno vnesel uporabniško ime in geslo in poskusi znova. ', 'error'));
                        $this->msg->addMessage(array('Si pozabil geslo? Nič hudega, novega lahko dobiš <a href="/uporabnik/geslo/" title="izgubljeno geslo">tule</a>', 'info'));
                        $this->_redirect('/');
                        //$this->view->message = 'Hm... Ni se mi uspelo prepričat, da si zares to kar praviš da si. Prosim preveri, če si pravilno vnesel uporabniško ime in geslo in poskusi znova.';
                        break;
                    case 12:
                        #RETRIEVING PASSWORD NOT VALID FORM:
                        $this->msg->addMessage(array('Nisi vnesel pravilnega email naslova.', 'error'));
                        $this->_redirect('/uporabnik/geslo/');
                        break;
                    case 13:
                        #NO E-MAIL IN DATABASE
                        $this->msg->addMessage(array('Ta E-Mail ni shranjen v bazi, si prepričan, da si se registriral?'), 'error');
                        $this->_redirect('/uporabnik/geslo/');
                    #REGISTER:
                    case 20:
                        #FIELDS EMPTY:
                        $this->msg->addMessage(array('Vem, da je naporno, ampak registracija ni mogoča, če ne izpolniš vseh polj!', 'error'));
                        $this->_redirect('/');
                        
                        break;
                    case 21:
                        #FIELDS EMPTY:
                        $this->msg->addMessage(array('Gesla se ne ujemata, ampak ne skrbet, to se zgodi tudi najboljšim. Poskusi šeenkrat, vrjamem, da ti bo uspelo!', 'error'));
                        $this->_redirect('/');
                        break;
                    case 22:
                        $this->msg->addMessage(array('Nisi navedel pravilnega e-mail naslova, čeprav bi ga moral!', 'error'));
                        $this->_redirect('/');
                        $this->view->message = '';
                        break;
                    #ADDJOB:
                    case 30:
                        $this->msg->addMessage(array('Nisi vnesel vseh potrebnih podatkov, to ni kul.', 'error'));
                        $this->_redirect('/sluzbe');
                        break;
                    #ADDWORK:
                    case 40:
                        $this->msg->addMessage(array('Baje se ne da delat dveh služb naenkrat :F', 'error'));
                        $this->_redirect('/delo');
                        break;
                    case 41:
                        #DELETE WORK:
                        $this->msg->addMessage(array('Tega pa ne moreš zbrisat, sorry.', 'error'));
                        $this->_redirect('/delo');
                        break;
                    case 23000:
                        #SQL ERRORS:
                        if(strstr($errors->exception->getMessage(), "1062 Duplicate entry")){
                            $this->msg->addMessage(array('Tvoj mejl je že shranjen v bazi, si pozabil, da si se že registriral?', 'error'));
                            $this->_redirect('/');
                        }                        
                        break;
                    default:
                        $this->view->message = 'Neznana napaka :S';
                        break;
                }
                
        }
        
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }


}

