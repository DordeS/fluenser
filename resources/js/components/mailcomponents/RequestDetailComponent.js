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
      btn1:'',btn2:'',btn1_status:true,btn2_status:true,
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
    var btn1, btn2, btn1_status, btn2_status;
    if(this.state.accountInfo.accountType == 'influencer') {
      btn2 = 'Decline';
      btn2_status = (this.state.requestInfo.status == 3) ? false : true;
      btn1 = (this.state.requestInfo.accepted)?'Accepted':'Accept';
      btn1_status = (this.state.requestInfo.accepted)?false:true;
      this.setState({btn1, btn2, btn1_status, btn2_status});
    } else {
      switch (this.state.requestInfo.status) {
        case 1:
          btn1 = 'Create Deposit';
          btn1_status = true;
          btn2 = 'Release Deposit';
          btn2_status = true;
          break;
        
        case 2:
          btn1 = 'Deposit made';
          btn1_status = false;
          btn2 = 'Release Deposit';
          btn2_status = true;
          break;

        case 3:
          btn1 = 'Deposit made';
          btn1_status = false;
          btn2 = 'Deposit released';
          btn2_status = false;
        default:
          break;
      }
      this.setState({btn1, btn2, btn1_status, btn2_status});
    }
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
      var status;
      switch (this.state.requestInfo.status) {
        case 1:
          status = "Waiting for Deposit."
          break;
        case 2:
          status = "Deposit made."
          break;
        case 3:
          status = 'Deposit release.'
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
            {
              (this.state.accountInfo.accountType == 'influencer')
              ? <a className="float-right bg-white text-gray-500" style={{lineHeight:'30px', padding:'5px', margin:"5px 15px 5px", borderRadius:'3px', boxShadow:'0 0 5px 0 rgb(100,100,100)'}} onClick={() => this.props.onChatClick(this.state.accountInfo.user_id, this.state.contactInfo.user_id)}>Chat</a>
              : <div></div>
            }
          </div>
          <div id="detailcontainer" style={{height:containerHeight, overflow:'auto'}}>
            <div className="w-10/12 md:max-w-xl relative mx-auto mb-16 px-3 mt-5">
              <img src={constant.baseURL + 'img/back-image/' + this.state.contactInfo.back_img + '.jpg'} alt={this.state.contactInfo.back_img}/>
              <img src={constant.baseURL + 'img/avatar-image/' + this.state.contactInfo.avatar + '.jpg'} alt={this.state.contactInfo.avatar} style={{width:'30%', position:'absolute', left:'50%', marginLeft:'-15%', bottom:'-25%', border:'3px solid white', boxShadow:'0 0 8px 0 #999'}} className='rounded-full'/>
            </div>
            <div id="contactInfo" className="mb-6">
              <p className="text-lg md:text-xl text-center">
                { this.state.contactInfo.name }
              </p>
              <p className="text-md md:text-lg text-center text-gray-500">
                { this.state.contactInfo.state + ' ' + this.state.contactInfo.country }
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
            <div id="buttons" className="pt-5 pb-3">
              <div className="w-3/5 mx-auto">
                <button href="#" className="float-left px-2 py-2 w-2/5 text-center text-sm md:text-md font-bold rounded text-white" style={{background:'rgb(92, 180, 184)'}} disabled={ !this.state.btn1_status } onClick={() => this.onClickBtn() }>{ this.state.btn1 }</button>
                <button href="#" className="float-right px-2 py-2 w-2/5 text-center text-sm md:text-md font-bold rounded text-white" style={{background:'rgb(92, 180, 184)'}} disabled={ !this.state.btn2_status } onClick={() => this.onClickBtn() }>{ this.state.btn2 }</button>
              </div>
            </div>
          </div>
        </div>
      );
    }
  }
}