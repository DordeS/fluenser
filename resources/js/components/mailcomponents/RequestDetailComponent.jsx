import React, { Component } from 'react';
import API from '../api';
import constant from '../const';
import $ from 'jquery';
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import { BrowserRouter } from "react-router-dom";
import ElementDemos from "./components/ElementDemos";

export class RequestDetailComponent extends Component {
  constructor(props) {
    super(props);
    this.state = {
      requestInfo: {},
      accountInfo: {},
      contactInfo: {},
      requestChats: {},
      isWaiting: true,
      showPayment: false,
    };
    this.updateOffer = this.updateOffer.bind(this);
    this.sendMessage = this.sendMessage.bind(this);
    this.onAccept = this.onAccept.bind(this);
    this.onDecline = this.onDecline.bind(this);
    this.createDeposit = this.createDeposit.bind(this);
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
        var requestChats = response.data.requestChats;
        this.setState({requestInfo, accountInfo, contactInfo, requestChats, isWaiting:false});
      }
    }).catch(error => {
      console.log(error);
    })

    console.log('component mounted!');

    Pusher.logToConsole = true;
  
    var pusher = new Pusher('da7cd3b12e18c9e2e461', {
      cluster: 'eu',
    });
    const this1 = this
    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', (data) => {
      if(data.trigger == 'requestChat'){
        if(data.requestChat.request_id == this.state.requestInfo.id) {
          const requestChats = this.state.requestChats;
          requestChats.push(data.requestChat);
          this.setState({requestChats});
        }
        if(data.request_id == this.state.requestInfo) {
          if(data.data == 'accepted') {
            var requestInfo = this.state.requestInfo;
            requestInfo.accepted = 1;
            this.setState({requestInfo});
          } else {
            this.props.back();
          }
        }
      } 
    });
  }

  componentDidUpdate() {
    var element = document.getElementById('requestChatContainer');
    console.log("+++++++");
    console.log(element);
    if(element != null) {
      element.scrollIntoView(false);
    }
  }

  createDeposit() {
    this.setState({
      showPayment: true,
    });
  }

  sendMessage(e) {
    e.preventDefault();
    var message = this.message.value;
    console.log(message);
    if(message != '') {
      const headers ={
        'Accept': 'application/json'
      };
        var api_token = $("meta[name=api-token]").attr('content');
      API.get('saveRequestChat/' + this.state.requestInfo.id + '/' + this.state.accountInfo.id + '/' + this.state.contactInfo.id + '/' + message + '?api_token=' + api_token, {
        headers: headers
      }).then((response) => {
        console.log(response);
      });
    }
  }

  updateOffer(e) {
    e.preventDefault();
    var price = this.price.value;
    var unit = this.currency.value;
    if(price != '') {
      console.log(price, unit);
      const headers ={
        'Accept': 'application/json'
      };
        var api_token = $("meta[name=api-token]").attr('content');
      API.get('updateRequest/' + this.state.requestInfo.id + '/' + price + '/' + unit + '?api_token=' + api_token, {
        headers: headers
      }).then((response) => {
        console.log(response.data);
        if(response.data.status == 200) {
          this.popUpToggle('hide');
          var requestinfo = this.state.requestInfo;
          requestinfo.amount = price;
          requestinfo.unit = unit;    
          this.setState({requestinfo});
        }
      });
    }
  }

  onAccept() {
    const headers ={
      'Accept': 'application/json'
    };
      var api_token = $("meta[name=api-token]").attr('content');
    API.get('acceptRequest/' + this.state.requestInfo.id + '?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      if(response.status == 200)
        var requestInfo = this.state.requestInfo;
        requestInfo.accepted = 1;
        this.setState({requestInfo});
    });
    this.confirmToggle('hide');
  }

  onDecline() {
    const headers ={
      'Accept': 'application/json'
    };
      var api_token = $("meta[name=api-token]").attr('content');
    API.get('declineRequest/' + this.state.requestInfo.id + '?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      if(response.status == 200)
        this.props.back();
    });
  }

  popUpToggle(a) {
    if(a == 'show') {
      $("div#modal").css('display', 'block');
      $("div#modal input#price").val(this.state.requestInfo.amount);
      $("div#modal option[value='" + this.state.requestInfo.unit + "']").attr('selected', true);
    }
    if(a == 'hide')
    $("div#modal").css('display', 'none');
  }

  confirmToggle(a) {
    switch (a) {
      case 'hide':
        $("div#confirmModal").hide();
        break;

      case 'accept':
        $('div.acceptConfirm').show();
        break;
      case 'decline':
        $('div.declineConfirm').show();
        break;
      case 'deposit':
        $('div.depositConfirm').show();
        break;
    
      default:
        break;
    }
  }


  render() {
    if(this.state.isWaiting) {
      return (
        <div className="max-w-sm mx-auto py-10 text-center">
          <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
        </div>
      )
    } else {
      var containerHeight = innerHeight - 225;
      console.log($('main').css('width'));
      var messengerWidth = $('main').css('width').slice(0, -2) - 110;
      console.log(this.state.requestInfo.images);
      var datetime = new Date(this.state.requestInfo.created_at);
      if(datetime.getHours() >= 12){
        var time = datetime.getHours() - 12 + ":" + datetime.getMinutes() + " PM";
      } else {
        var time = datetime.getHours() + ":" + datetime.getMinutes() + " AM";
      }
      var month = constant.month[datetime.getMonth()];
      var day = datetime.getDate();
      datetime = time + ', ' + month + ' ' + day;
      if (this.state.showPayment) {
        return (
          <BrowserRouter>
            <Elements stripe={loadStripe('pk_test_51HtrYKJyHziuhAX0GAQs9a6fajsFjcQanWHSmb384TC5aJLZdsPv4oCRAbUJ20kHozUSmkACPtk6abdlWzICm6k600VHofe1zg')}>
              <ElementDemos
                requestID = {this.props.requestID}
                afterDeposit = {() => this.props.afterDeposit()}
              />
            </Elements>
          </BrowserRouter>
            );
      } else {
        return (
          <div>
            <div id="modal" className="h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
                <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
                  <div className="rounded-t-xl h-10" style={{ background:'linear-gradient(to right, RGB(5,235,189), RGB(19,120,212))' }}>
                    <p className="text-md md:text-lg text-center text-white font-bold leading-10">Update Offer</p>
                    <a className="block h-6 w-6 absolute -top-2 -right-2 rounded-full bg-white text-center" onClick={() => this.popUpToggle('hide')} style={{ boxShadow:'0 0 8px #353535' }}>
                      <span className="leading-6"><i className="fas fa-times"></i></span>
                    </a>
                  </div>
  
                  <div className="w-11/12 mx-auto grid grid-cols-2 gap-x-4">
                    <div className="col-span-1">
                      <label htmlFor="price" className="block text-xs md:text-sm font-medium text-gray-700 mt-4">Project Amount<sup style={{color:'red'}}>*</sup>
                      </label>
                      <input type="number" name="price" id="price" className="w-full rounded-lg text-xs md:text-sm bg-gray-200 text-gray-500 border-none" ref={(e) => this.price = e}/>
                    </div>
                    <div className="col-span-1">
                      <label htmlFor="price" className="block text-xs md:text-sm font-medium text-gray-700 mt-4">Currency<sup style={{color:'red'}}>*</sup>
                      </label>
                      <select name="currency" id="currency" className="w-full rounded-lg text-xs md:text-sm bg-gray-200 text-gray-500 border-none" ref={(e) => this.currency = e}>
                        <option value="gbp">GBP</option>
                        <option value="usd">USD</option>
                        <option value="eur">EUR</option>
                      </select>
                    </div>
                  </div>
                  <div className="w-11/12 mx-auto mt-3">
                    <button className="block mx-auto px-4 py-1 rounded-lg text-white text-sm md:text-md" style={{ background:'rgb(88,183,189)' }} onClick={this.updateOffer}>
                      Update
                    </button>
                  </div>
                </div>
            </div>
  
  
            <div id="confirmModal" className="acceptConfirm h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
                <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
                  <div className="rounded-t-xl h-10" style={{ background:'linear-gradient(to right, RGB(5,235,189), RGB(19,120,212))' }}>
                    <p className="text-md md:text-lg text-center text-white font-bold leading-10">Accept Request</p>
                    <a className="block h-6 w-6 absolute -top-2 -right-2 rounded-full bg-white text-center" onClick={() => this.confirmToggle('hide')} style={{ boxShadow:'0 0 8px #353535' }}>
                      <span className="leading-6"><i className="fas fa-times"></i></span>
                    </a>
                  </div>
                  <div className="w-10/12 mx-auto">
                    <p className="text-center text-md md:text-lg text-gray-500 mt-4">Are you sure to accept this request?</p>
                  </div>
                  <div className="w-11/12 mx-auto mt-3" id="confirmBtn">
                    <button className="block mx-auto px-4 py-1 rounded-lg text-white text-sm md:text-md" style={{ background:'rgb(88,183,189)' }} onClick={this.onAccept}>Accept</button>
                  </div>
                </div>
            </div>
  
            <div id="confirmModal" className="declineConfirm h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
                <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
                  <div className="rounded-t-xl h-10" style={{ background:'linear-gradient(to right, RGB(5,235,189), RGB(19,120,212))' }}>
                    <p className="text-md md:text-lg text-center text-white font-bold leading-10">Decline Request</p>
                    <a className="block h-6 w-6 absolute -top-2 -right-2 rounded-full bg-white text-center" onClick={() => this.confirmToggle('hide')} style={{ boxShadow:'0 0 8px #353535' }}>
                      <span className="leading-6"><i className="fas fa-times"></i></span>
                    </a>
                  </div>
                  <div className="w-10/12 mx-auto">
                    <p className="text-center text-md md:text-lg text-gray-500 mt-4">Are you sure to decline this request?</p>
                  </div>
                  <div className="w-11/12 mx-auto mt-3" id="confirmBtn">
                    <button className="block mx-auto px-4 py-1 rounded-lg text-white text-sm md:text-md" style={{ background:'rgb(88,183,189)' }} onClick={this.onDecline}>Decline</button>
                  </div>
                </div>
            </div>
  
            <div id="confirmModal" className="depositConfirm h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
                <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
                  <div className="rounded-t-xl h-10" style={{ background:'linear-gradient(to right, RGB(5,235,189), RGB(19,120,212))' }}>
                    <p className="text-md md:text-lg text-center text-white font-bold leading-10">Create Deposit</p>
                    <a className="block h-6 w-6 absolute -top-2 -right-2 rounded-full bg-white text-center" onClick={() => this.confirmToggle('hide')} style={{ boxShadow:'0 0 8px #353535' }}>
                      <span className="leading-6"><i className="fas fa-times"></i></span>
                    </a>
                  </div>
                  <div className="w-10/12 mx-auto">
                    <p className="text-center text-md md:text-lg text-gray-500 mt-4">Are you sure to create deposit for this request?</p>
                  </div>
                  <div className="w-11/12 mx-auto mt-3" id="confirmBtn">
                    <button className="block mx-auto px-4 py-1 rounded-lg text-white text-sm md:text-md" style={{ background:'rgb(88,183,189)' }} onClick={this.createDeposit}>Create Deposit</button>
                  </div>
                </div>
            </div>
  
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
            <div id="requestChatContainer" className="bg-gray-100 pt-2" style={{height:containerHeight+'px', overflow:'auto'}}>
              <div id="requestdetail">
                <p className="text-center text-xs md:text-sm text-gray-400">{ datetime }</p>
                <div className="w-10/12 mx-auto bg-white py-1 py-2">
                  <p className="text-center text-gray-700 text-sm md:text-md">
                    { this.state.requestInfo.content }
                  </p>
                  <p className="text-gray-500 text-xs md:text-sm ml-2">
                    Offer: <span className="text-gray-600">{ this.state.requestInfo.amount + this.state.requestInfo.unit.toUpperCase() }</span>
                  </p>
                  <div id="reqeustImages" className="mt-2">
                    {
                      this.state.requestInfo.images.map((requestInfo, key) => {
                        return(
                        <div key={key} className="float-left ml-2">
                          <div>
                            <img src={constant.baseURL + 'img/task-image/'  + requestInfo.image + '.jpg'} alt="" style={{ width:'40px', height:'40px' }}/>
                          </div>
                          <div className="clearfix"></div>
                        </div>
                        );
                      })
                    }
                    <div className="clearfix"></div>
                  </div>
                </div>
              </div>
              {
                (this.state.requestChats.length == 0) ?
                <div id="chatContainer">
                  <p className="text-sm md:text-md text-center">
                    Start your chat here.
                  </p>
                </div>
                :
                <div id="chatContainer">
                {
                  this.state.requestChats.map((chat, i)=>{
                    var datetime = new Date(chat.created_at);
                    if(datetime.getHours() >= 12){
                      var time = datetime.getHours() - 12 + ":" + datetime.getMinutes() + " PM";
                    } else {
                      var time = datetime.getHours() + ":" + datetime.getMinutes() + " AM";
                    }
                    var month = constant.month[datetime.getMonth()];
                    var day = datetime.getDate();
                    datetime = time + ', ' + month + ' ' + day;
                    var isUser = (chat.send_id == this.state.accountInfo.id) ? true : false;
                    return(
                      <div key={i} className="w-full mx-auto rounded px-2 mt-5">
                          {isUser
                            ? <div>
                                <div className="relative float-right">
                                  <div style={{border:'1px solid #999', float:'right'}}>
                                    <p className="text-sm px-4 py-2 text-gray-700">
                                      {chat.content}
                                    </p>
                                  </div>
                                  <div className="clearfix"></div>
                                  <p className="text-xs text-gray-500 mt-2 absolute left-0">
                                    {datetime}
                                  </p>
                                  <div className="clearfix"></div>
                                </div>
                                <div className="clearfix"></div>
                              </div>
                            : <div>
                                <div className="relative float-left">
                                  <div className="bg-white" style={{ float:'left'}}>
                                    <p className="text-sm px-4 py-2 text-gray-700">
                                      {chat.content}
                                    </p>
                                  </div>
                                  <div className="clearfix"></div>
                                  <p className="text-xs text-gray-500 mt-2 absolute right-0">
                                      {datetime}
                                  </p>
                                  <div className="clearfix"></div>
                                </div>
                                <div className="clearfix"></div>
                              </div>                        
                            }
                          </div>
                        );
                      })
                    }
                </div>
              }
              <div id="buttons" className="mt-16">
                {
                  (this.state.accountInfo.accountType == 'influencer')
                  ?
                  <div className="w-full grid grid-cols-2 gap-x-4">
                    <div className="col-span-1">
                      {
                        (this.state.requestInfo.accepted)
                        ?
                        <button className="block mx-auto px-4 py-1 rounded-sm text-white text-sm md:text-md bg-gray-500" disabled>Accepted</button>
                        :
                        <button className="block mx-auto px-4 py-1 rounded-sm text-white text-sm md:text-md bg-green-600" onClick={() => this.confirmToggle('accept')}>Accept</button>
                      }
                    </div>
                    <div className="col-span-1">
                      <button className="block mx-auto px-4 py-1 rounded-sm text-white text-sm md:text-md bg-red-600" onClick={() => this.confirmToggle('decline')}>Decline</button>
                    </div>
                  </div>
                  :
                  <div className="w-full">
                    {
                      (this.state.requestInfo.accepted)
                      ?
                      <button className="block mx-auto px-4 py-1 rounded-sm text-white text-sm md:text-md" style={{ background:'rgb(88,183,189)' }} onClick={() => this.confirmToggle('deposit')}>Create Deposit</button>
                      :
                      <button className="block mx-auto px-4 py-1 rounded-sm text-white text-sm md:text-md" style={{ background:'rgb(88,183,189)' }} onClick={() => this.popUpToggle('show')}>Update offer</button>
                    }
                  </div>
                }
              </div>
              <div className="h-40"></div>
            </div>
            <div className="w-full md:max-w-7xl fixed" style={{bottom:'55px'}}>
              <div className="w-full bg-white" style={{height:'60px', borderTop:'1px solid lightgray'}}>
                <div className="float-right">
                  <a onClick={this.sendMessage} style={{display:'block',height:'60px', width:'60px', background:'rgb(88,183,189)', fontSize:'20px', lineHeight:'60px', color:'white', textAlign:'center'}}>
                    <i className="fas fa-paper-plane"></i>
                  </a>
                </div>
                <div className="float-left">
                  <a href="#" style={{fontSize:'20px', lineHeight:'60px', padding:'0 10px'}} className="text-gray-400">
                    <i className="fas fa-paperclip"></i>
                  </a>                  
                </div>
                <div>
                  <input type="text" name="message" id="message" className="w-full border-none" autoComplete="off" placeholder="Type your message ..." ref={(e) => this.message = e} style={{width:messengerWidth+'px', margin:'10px 0'}}/>
                </div>
                <div className="clearfix"></div>
              </div>
            </div>
          </div>
        );
      }
    }
  }
}