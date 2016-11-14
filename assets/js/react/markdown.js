import React from 'react';

var $ = require('jquery');
const markdown = require('markdown').markdown;

class Markdown extends React.Component {
  constructor(props) {
    super(props);
    this.inputText = this.inputText.bind(this);
    this.createMarkup = this.createMarkup.bind(this);
    this.state = {
      textareaValue: ''
    }
  }
  componentDidMount() {
    $(document).delegate('#textbox', 'keydown', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 9) {
        e.preventDefault();
        var start = $(this).get(0).selectionStart;
        var end = $(this).get(0).selectionEnd;

        // set textarea value to: text before caret + tab + text after caret
        $(this).val($(this).val().substring(0, start)
                    + "\t"
                    + $(this).val().substring(end));

        // put caret at right position again
        $(this).get(0).selectionStart =
        $(this).get(0).selectionEnd = start + 1;
      }
    });
    this.setState({
      textareaValue: this.props.textareaValue
    });
  }
  createMarkup() {
    return {__html: markdown.toHTML(this.state.textareaValue)};
  }
  inputText(e) {
    this.setState({
      textareaValue: e.target.value
    });
    window.currentTextareaValue = e.target.value;
  }
  render() {
    return (
      <div className="markdown clearfix">
        <div className="edit">
          <textarea id="textbox" className="textarea" onChange={this.inputText} defaultValue={this.props.textareaValue}></textarea>
        </div>
        <div className="view" dangerouslySetInnerHTML={this.createMarkup()}></div>
      </div>
    )
  }
}

module.exports = Markdown;
