<?php
require_once 'PHPUnit/Framework.php';
require_once(dirname(__FILE__).'/../../config.php');
require_once dirname(__FILE__).'/../../lib/Validator.class.php';

/**
 * Test class for PandraValidator.
 * Generated by PHPUnit on 2010-01-09 at 11:52:24.
 */
class PandraValidatorTest extends PHPUnit_Framework_TestCase {
    /**
     * @var    PandraValidator
     * @access protected
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
        $this->object = new PandraValidator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {

    }

    public function test_notempty() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('string', 'notemptyLabel', 'notempty', $errors));

        $this->assertFalse(PandraValidator::check('', 'notemptyLabel', 'notempty', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_isempty() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('', 'isemptyLabel', 'isempty', $errors));

        $this->assertFalse(PandraValidator::check('string', 'isemptyLabel', 'isempty', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_int() {
        $errors = array();
        $this->assertTrue(PandraValidator::check(1, 'intLabel', 'int', $errors));

        $this->assertFalse(PandraValidator::check('string', 'intLabel', 'int', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_float() {
        $errors = array();
        $this->assertTrue(PandraValidator::check(1, 'floatLabel', 'float', $errors));

        $this->assertFalse(PandraValidator::check('string', 'floatLabel', 'float', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_numeric() {
        $errors = array();
        $this->assertTrue(PandraValidator::check(1, 'notemptyLabel', 'numeric', $errors));

        $this->assertFalse(PandraValidator::check('', 'notemptyLabel', 'numeric', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_string() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('string', 'stringLabel', 'string', $errors));

        $this->assertFalse(PandraValidator::check(1, 'stringLabel', 'string', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_bool() {
        $errors = array();
        $this->assertTrue(PandraValidator::check(true, 'boolLabel', 'bool', $errors));

        $this->assertFalse(PandraValidator::check('NO', 'boolLabel', 'bool', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_maxlength() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('string', 'maxLabel', 'maxlength=20', $errors));

        $this->assertFalse(PandraValidator::check('abc', 'maxLabel', 'maxlength=2', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_minlength() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('string', 'minLabel', 'minlength=2', $errors));

        $this->assertFalse(PandraValidator::check('string', 'minLabel', 'minlength=20', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_enum() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('c', 'enumLabel', 'enum=a,b,c', $errors));

        $this->assertFalse(PandraValidator::check('d', 'enumLabel', 'enum=a,b,c', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_email() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('foo@bar.com', 'emailLabel', 'email', $errors));

        $this->assertFalse(PandraValidator::check('foobar.com', 'emailLabel', 'email', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_url() {
        $errors = array();
        $this->assertTrue(PandraValidator::check('http://www.phpgrease.net', 'urlLabel', 'url', $errors));

        $this->assertFalse(PandraValidator::check('foobar', 'urlLabel', 'url', $errors));
        $this->assertTrue(!empty($errors));
    }

    public function test_uuid() {
        $errors = array();
        $this->assertTrue(PandraValidator::check(UUID::generate(), 'timeuuid', 'uuid', $errors));

        $this->assertFalse(PandraValidator::check('foobar', 'timeuuid', 'uuid', $errors));
        $this->assertTrue(!empty($errors));
    }


    public function testComplex() {
        $errors = array();

        // test multiple validations per field
        $this->assertTrue(PandraValidator::check('http://www.phpgrease.net', 'urlLabel', array('url', 'maxlength=40'), $errors));

        $this->assertFalse(PandraValidator::check('wwwphpgrease', 'urlLabel', array('url', 'maxlength=10'), $errors));
        // expect errors for both the url and max length
        $fieldError = array_pop($errors);
        $this->assertTrue(count($fieldError['urlLabel']) == 2);

        // Test internal composites
        $this->assertTrue(PandraValidator::check('hello world', 'strRegularLabel', 'stringregular', $errors));
        $this->assertFalse(PandraValidator::check('', 'strRegularLabel', 'stringregular', $errors));

        $this->assertTrue(PandraValidator::check('hello world', 'str20Label', 'string20', $errors));
        $this->assertFalse(PandraValidator::check('abcdefghijklmnopqrstuvwxyz', 'str20Label', 'string20', $errors));
    }
}
?>