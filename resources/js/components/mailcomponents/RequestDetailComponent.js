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
      isWaiting: true
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
        console.log(response.data.requestInfo);
        var requestInfo = response.data.requestInfo;
        var accountInfo = response.data.accountInfo;
        this.setState({requestInfo, accountInfo, isWaiting:false});
      }
    }).catch(error => {
      console.log(error);
    })
    console.log("mounted component message");
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
      var status;
      switch (this.state.requestInfo.status) {
        case 1:
          status = "Waiting for deposite."
          break;
        case 2:
          status = "Deposite made."
          break;
        case 3:
          status = 'Deposite release.'
          break;
        default:
          break;
      }
      return (
        <div>
          <div className="w-full" style={{background:'rgb(92, 180, 184)', borderRadius:'0 0 10px 10px', height:'70px', paddingTop:'10px'}}>
            <div style={{float:'left', marginLeft:'15px'}}>
              <a className="text-center text-gray-300" onClick={()=> this.props.back()} style={{lineHeight:'50px'}}>
                <i className="fas fa-chevron-left"></i>
              </a>
            </div>
            <span className="text-lg md:text-xl text-center text-white font-bold ml-5" style={{lineHeight:'50px'}}>Request Detail</span>
            <button className="float-right bg-white text-gray-500" style={{lineHeight:'30px', padding:'5px', margin:"5px 15px 5px", borderRadius:'3px', boxShadow:'0 0 5px 0 rgb(100,100,100)'}}>Chat</button>
          </div>
          <div id="detailcontainer" style={{height:containerHeight, overflow:'auto'}}>
            <div className="w-10/12 md:max-w-xl relative mx-auto mb-16 px-3 mt-5">
              <img src={constant.baseURL + 'img/back-image/' + this.state.accountInfo.back_img + '.jpg'} alt={this.state.accountInfo.back_img}/>
              <img src={constant.baseURL + 'img/avatar-image/' + this.state.accountInfo.avatar + '.jpg'} alt={this.state.accountInfo.avatar} style={{width:'30%', position:'absolute', left:'50%', marginLeft:'-15%', bottom:'-25%', border:'3px solid white', boxShadow:'0 0 8px 0 #999'}} className='rounded-full'/>
            </div>
            <div id="accountInfo" className="mb-6">
              <p className="text-lg md:text-xl text-center">
                { this.state.accountInfo.name }
              </p>
              <p className="text-md md:text-lg text-center text-gray-500">
                { this.state.accountInfo.state + ' ' + this.state.accountInfo.country }
              </p>
            </div>
            <div id="requestInfo" className="px-5 md:px-10">
              <p className="text-lg text-center md:text-xl font-bold py-2" style={{fontFamily:"'Josefin Sans', sans-serif"}}>
                { this.state.requestInfo.title }
              </p>
              <span className="float-right text-xs md:text-sm font-normal text-gray-500">
                { this.state.requestInfo.amount + ' ' + this.state.requestInfo.unit }
              </span>
              <div className="clearfix"></div>
              <p className="text-xs md:text-sm mx-3 py-2">{ this.state.requestInfo.content }</p>
              <p className="text-right text-xs text-gray-500 md:text-sm">
                { this.state.requestInfo.created_at }
              </p>
            </div>
            <div id="status" className="pt-8 pb-3">
              <p className="font-bold text-center text-md md:text-lg" style={{fontFamily:"'Josefin Sans', sans-serif"}}>Status</p>                
              <p className="text-center text-sm md:text-md">
                { status } 
              </p>
            </div>
          </div>
        </div>
      );
    }
  }
}