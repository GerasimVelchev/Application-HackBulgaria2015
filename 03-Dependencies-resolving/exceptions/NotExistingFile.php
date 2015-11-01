<?php
    namespace Exceptions;
    use Exception;

    class NotExistingFile extends Exception {

        protected $_errors;

        public function __construct($errors = null, $message = null, $code = 0, Exception $previous = null) {
            $this->_setErrors($errors);
             parent::__construct($message, $code, $previous);
        }

        protected function _setErrors($errors) {
            $this->_errors = $errors;
        }

        public function getErrors() {
            return "Error: " . $this->_errors . " does not exist.<br/>";
        }
    }
?>