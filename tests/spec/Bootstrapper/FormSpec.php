<?php

namespace spec\Bootstrapper;

use Illuminate\Html\HtmlBuilder;
use Mockery;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormSpec extends ObjectBehavior
{
    function let()
    {
        $url = Mockery::mock('Illuminate\Routing\UrlGenerator');
        $url->shouldReceive('current')->andReturn('foo');
        $token = "foo";

        $this->beConstructedWith(new HtmlBuilder($url), $url, $token);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bootstrapper\Form');
        // Since it should extend...
        $this->shouldHaveType('Illuminate\Html\FormBuilder');
    }

    /**
     * Sanity check!
     */
    function it_can_close_the_form()
    {
        $this->close()->shouldBe("</form>");
    }

    /**
     * More sanity checks!
     */
    function it_can_open_a_form()
    {
        $this->open()->shouldBe('<form method="POST" action="foo" accept-charset="UTF-8"><input name="_token" type="hidden" value="foo">');
    }

    function it_can_open_an_inline_form()
    {
        $this->inline()->shouldBe('<form method="POST" action="foo" accept-charset="UTF-8" class="form-inline"><input name="_token" type="hidden" value="foo">');

        $this->inline(['class' => 'option'])->shouldBe('<form method="POST" action="foo" accept-charset="UTF-8" class="form-inline option"><input name="_token" type="hidden" value="foo">');
    }

    function it_can_open_a_horizontal_form()
    {
        $this->horizontal()->shouldBe('<form method="POST" action="foo" accept-charset="UTF-8" class="form-horizontal"><input name="_token" type="hidden" value="foo">');

        $this->horizontal(['class' => 'option'])->shouldBe('<form method="POST" action="foo" accept-charset="UTF-8" class="form-horizontal option"><input name="_token" type="hidden" value="foo">');
    }

    function it_can_show_validation()
    {
        $validations = ['success', 'warning', 'error'];

        foreach($validations as $validation) {
            $this->$validation('<div>label</div>', '<div>input</div>')->shouldBe("<div class='form-group has-{$validation}'><div>label</div><div>input</div></div>");
            $this->$validation('<div>label</div>', '<div>input</div>', ['class' => 'foo'])->shouldBe("<div class='form-group has-{$validation} foo'><div>label</div><div>input</div></div>");
            $this->$validation('<div>label</div>', '<div>input</div>', ['foo' => 'bar'])->shouldBe("<div foo='bar' class='form-group has-{$validation}'><div>label</div><div>input</div></div>");
        }
    }

    function it_can_show_feedback()
    {
        $this->feedback('<div>label</div>', '<div>input</div>', 'foo')->shouldBe("<div class='form-group has-feedback'><div>label</div><div>input</div><span class='glyphicon glyphicon-foo form-control-feedback'></span></div>");

        $this->feedback('<div>label</div>', '<div>input</div>', 'foo', ['class' => 'foo'])->shouldBe("<div class='form-group has-feedback foo'><div>label</div><div>input</div><span class='glyphicon glyphicon-foo form-control-feedback'></span></div>");

        $this->feedback('<div>label</div>', '<div>input</div>', 'foo', ['bar' => 'foo'])->shouldBe("<div bar='foo' class='form-group has-feedback'><div>label</div><div>input</div><span class='glyphicon glyphicon-foo form-control-feedback'></span></div>");
    }

    function it_can_show_help_blocks()
    {
        $this->help('foo')->shouldBe("<span class='help-block'>foo</span>");
        $this->help('foo', ['class' => 'foo'])->shouldBe("<span class='help-block foo'>foo</span>");
        $this->help('foo', ['data-class' => 'foo'])->shouldBe("<span data-class='foo' class='help-block'>foo</span>");
    }
}