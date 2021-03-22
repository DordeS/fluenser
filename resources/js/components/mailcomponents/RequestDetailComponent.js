import React, { Component } from 'react';
import API from '../api';
import constant from '../const';
import $, { error } from 'jquery';

export class RequestDetailComponent extends Component {
  constructor() {
    super();
    this.state = {
      requestInfo: {},
      accountInfo: {},
      contactInfo: {},
      isWaiting: true,
    };
  }

  componentDidMount()
  {
    // request
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('requestDetail/' + this.props.requestID + '?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      if(response.status == 200) {
        console.log('-------------');
        console.log(response.data);
        var requestInfo = response.data.requestInfo;
        var accountInfo = response.data.accountInfo;
        var contactInfo = response.data.contactInfo;
        this.setState({requestInfo, accountInfo, contactInfo, isWaiting:false});
      }
    }).catch(error => {
      console.log(error);
    })
    console.log("mounted component message");
}

  onClickBtn() {
    console.log('clicked');
  }
  
  render() {
    if(this.state.isWaiting) {
      return (
        <div className="max-w-sm mx-auto py-10 text-center">
          <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
        </div>
      )
    } else {
      var containerHeight = innerHeight - 123;
      return (
        <div>
          <div className="w-full bg-white" style={{height:'70px', paddingTop:'10px'}}>
            <div style={{float:'left', marginLeft:'15px'}}>
              <a className="text-center text-gray-500" onClick={()=> this.props.back()} style={{lineHeight:'50px'}}>
                <i className="fas fa-chevron-left"></i>
              </a>
            </div>
            <div className="float-left ml-4">
              <img src={constant.baseURL + 'img/avatar-image/' + this.state.contactInfo.avatar + '.jpg'} alt={this.state.contactInfo.avatar} className="rounded-full" style={{ width:'50px', height:'50px' }}/>
            </div>
            <p className="float-left text-lg md:text-xl text-center text-gray-500 font-bold ml-4">{this.state.contactInfo.name} <br/>
            <span className="text-sm md:text-md font-normal">
              Last seen 5 min ago
            </span>
            </p>
          </div>
          <div id="requestChatContainer" className="bg-gray-100 pt-5">
            <div id="requestdetail">
              <div className="w-10/12 mx-auto bg-white py-1 py-2">
                <p className="text-center text-gray-700">
                  { this.state.requestInfo.content }
                </p>
              </div>
              <div id="reqeustImages">
                {
                  this.state.requestInfo.images.map((key, requestInfo) => {
                    <div key={key}>
                      <div key={key} className="float-left">

                        <img src={constant.baseURL + 'img/task-image/'  + requestInfo + '/jpg'} alt=""/>
                      </div>

                      <div className="clearfix"></div>
                    </div>
                  })
                }
              </div>
            </div>
          </div>
        </div>
      );
    }
  }
}