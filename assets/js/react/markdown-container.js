import React from 'react';
import Markdown from './markdown';

var $ = require('jquery');

class MarkdownContainer extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {

  }
  render() {
    var typeTitle = { cp: '产品', cs: '测试', kf: '开发', sj: '设计' };
    const self = this;
    return (
      <div>
        {/* Nav tabs */}
        <ul className="nav nav-tabs" role="tablist">
          {this.props.data.contentList.map(function (item, i) {
            return (<li key={i} className={i === 0 ? 'active' : ''}><a href={`#${item.type_name}`} data-toggle="tab">{typeTitle[item.type_name]}</a></li>);
          })}
        </ul>
        {/* Tab panes */}
        <div className="tab-content">
          {this.props.data.contentList.map(function (item, i) {
            return (<div className={i === 0 ? 'tab-pane fade active in' : 'tab-pane'} key={i} id={item.type_name}>
              <Markdown textareaValue={item.content} />
            </div>);
          })}
        </div>
      </div>
    );
  }
}

MarkdownContainer.propTypes = {
  data: React.PropTypes.object.isRequired
};

module.exports = MarkdownContainer;
