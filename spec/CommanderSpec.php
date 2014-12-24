<?php

namespace spec\Lijinma;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommanderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Lijinma\Commander');
    }

    function it_can_be_set_the_version_and_return_self()
    {
        $this->version('1.0.0')->shouldReturn($this);
    }

//    function it_can_add_command()
//    {
//        $this->command('rmdir <dir> [otherDirs...]', 'Remove the directory', function(){});
//    }

//    function it_can_add_options()
//    {
//        $this->option('-p, --peppers', 'Add peppers');
//        $this->options->shouldBe([]);
//    }

    function it_has_help_option()
    {
        $this->options->shouldHaveCount(1);
    }

    function it_can_add_multiple_options()
    {
        $this->option('-p, --peppers', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $this->options->shouldHaveCount(3);
    }

    function it_can_nomalize_args()
    {
        $this->normalize(['-a'])->shouldReturn(['-a']);

        $this->normalize(['--test'])->shouldReturn(['--test']);

        $this->normalize(['-abc'])->shouldReturn(['-a', '-b', '-c']);

        $this->normalize(['--name=jinma'])->shouldReturn(['--name', 'jinma']);

        $this->normalize(['-n', 'lijinma', '--sex', 'male'])->shouldReturn(['-n', 'lijinma', '--sex', 'male']);
    }

    function it_can_check_whether_the_arg_exist_in_options()
    {

        $this->option('-p, --peppers', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $this->optionFor('-p')->shouldReturnAnInstanceOf('Lijinma\Option');
        $this->optionFor('--bbq')->shouldReturnAnInstanceOf('Lijinma\Option');
        $this->optionFor('--unkownOption')->shouldReturn(false);

    }

    function it_can_add_property()
    {
        $this->createProperty('key', 'value');

        $this->key->shouldBe('value');
    }

    function it_throws_exception_when_add_a_existed_property()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringCreateProperty('name', 'value');
    }


    function it_can_parse_options()
    {
        $this->option('-p, --peppers', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $args = ['-p', 'pepper1'];

        $this->parseOptions($args);

        $this->peppers->shouldBe('pepper1');
    }

    function it_will_throw_exception_if_required_option_is_not_set()
    {
        $this->option('-p, --peppers <pepper-name>', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $args = ['-p', '-b'];

        $this->shouldThrow('\InvalidArgumentException')->duringParseOptions($args);

        $args = ['-p'];

        $this->shouldThrow('\InvalidArgumentException')->duringParseOptions($args);

    }


    function it_can_add_name_and_args_when_parse_argv()
    {
        $this->option('-p, --peppers', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $argv = ['test.php', '-p', 'pepper1'];

        $this->parse($argv);

        $this->args->shouldBe(['-p', 'pepper1']);

        $this->name->shouldBe('test.php');
    }

    function it_can_parse_argv_and_create_property()
    {
        $this->option('-p, --peppers', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $argv = ['test.php', '-p', 'pepper1'];

        $this->parse($argv);

        $this->peppers->shouldBe('pepper1');
    }




    //help

//    function it_can_show_the_help()
//    {
//        $argv = ['test.php', '-h'];
//
//        $this->parse($argv)->shouldBe('help');
//    }

    function it_can_get_the_largest_option_width()
    {
        $this->option('-p, --peppers', 'Add peppers')
            ->option('-b, --bbq', 'Add bbq sauce');

        $this->getLargestOptionWidth()->shouldReturn(13);
    }


    function it_can_pad_string_to_width()
    {
        $this->pad('-p, --peppers', 20)->shouldReturn('-p, --peppers       ');
    }


    function it_can_get_the_option_help()
    {
        $this->option('-p, --peppers', 'Add peppers');

        $this->getOptionHelp()->shouldReturn(
            [
                '    -h, --help     Output usage information',
                '    -p, --peppers  Add peppers'
            ]
        );
    }

    function it_can_get_the_usage()
    {
        $this->usage()->shouldReturn('[options]');
    }

}
