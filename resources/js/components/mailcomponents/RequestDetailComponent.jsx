import React, { Component, useEffect, useState } from 'react';
import API from '../api';
import constant from '../const';
import $ from 'jquery';
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import { BrowserRouter } from "react-router-dom";
import ElementDemos from "./components/ElementDemos";

const RequestDetailComponent = (props) => {
  const [requestInfo, setRequestInfo] = useState({});
  const [accountInfo, setAccountInfo] = useState({});
  const [contactInfo, setContactInfo] = useState({});
  const [requestChats, setRequestChats] = useState([]);
  const [isWaiting, setIsWaiting] = useState(true);
  const [showPayment, setShowPayment] = useState(false);
  const [price, setPrice] = useState('');
  const [currency, setCurrency] = useState('gbp');
  const [message, setMessage] = useState('');
  const [update, setUpdate] = useState(false);

  useEffect(() => {
    let isMount = false;
    $("nav").hide();
    // request
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('requestDetail/' + props.requestID + '?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      if(response.status == 200) {
        console.log('-------------');
        console.log(response.data);
        var requestInfo = response.data.requestInfo;
        var accountInfo = response.data.accountInfo;
        var contactInfo = response.data.contactInfo;
        var requestChats = response.data.requestChats;
        setRequestInfo(requestInfo);
        setRequestChats(requestChats);
        setAccountInfo(accountInfo);
        setContactInfo(contactInfo);
        setPrice(requestInfo.amount);
        setCurrency(requestInfo.unit);
        setIsWaiting(false);
      }
    }).catch(error => {
      console.log(error);
    })

    console.log('component mounted!');

    Pusher.logToConsole = true;
  
    var pusher = new Pusher('da7cd3b12e18c9e2e461', {
      cluster: 'eu',
    });
    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', (data) => {
      console.log('qwer');
      console.log(data);

      if(data.trigger == 'requestChat'){
        if(!isMount) {
          if(data.requestChat.request_id == requestInfo.id) {
            var tmpRequestChat = requestChats;
            tmpRequestChat.push(data.requestChat);
            setRequestChats(tmpRequestChat);
            setUpdate(!update);
          }
        }
      }

      if(data.trigger == 'acceptRequest') {
        if(data.data == 'accepted' && data.request_id == requestInfo.id) {
          var requestInfos = requestInfo;
          requestInfos.accepted = 1;
          setRequestInfo(requestInfos);
          setUpdate(!update);
        }
      }

      // if(data.trigger == 'request_status') {
      //   var requestInfos = requestInfo;
      //   requestInfos.status = data.status;
      //   setRequestInfo(requestInfos);
      //   setUpdate(!update);
      // }
    });
    if(!isMount) {
      var element = document.getElementById('requestChatContainer');
      console.log(element);
      if(element != null) {
        console.log("+++++++");
        element.scrollIntoView(false);
      }
    }
    return() => {
      isMount = true;
    }
  }, [isWaiting, requestInfo.status, update] );

  const handlePriceChange = (e) => {
    setPrice(e.target.value);
  }

  const handleCurrencyChange = (e) => {
    setCurrency(e.target.value);
  }

  const handleMessageChange = (e) => {
    setMessage(e.target.value);
  }

  const createDeposit = () => {
    setShowPayment(!showPayment);
  }

  const sendMessage = (e) => {
    e.preventDefault();
    console.log(message);
    if(message != '') {
      const headers ={
        'Accept': 'application/json'
      };
        var api_token = $("meta[name=api-token]").attr('content');
      API.get('saveRequestChat/' + requestInfo.id + '/' + accountInfo.id + '/' + contactInfo.id + '/' + message + '?api_token=' + api_token, {
        headers: headers
      }).then((response) => {
        console.log(response);
        setMessage('');
        setUpdate(!update);
      });
    }
  }

  const updateOffer = (e) => {
    e.preventDefault();
    var unit = currency;
    if(price != '') {
      console.log(price, unit);
      const headers ={
        'Accept': 'application/json'
      };
        var api_token = $("meta[name=api-token]").attr('content');
      API.get('updateRequest/' + requestInfo.id + '/' + price + '/' + unit + '?api_token=' + api_token, {
        headers: headers
      }).then((response) => {
        console.log(response.data);
        if(response.data.status == 200) {
          popUpToggle('hide');
          var requestInfos = requestInfo;
          requestInfos.amount = price;
          requestInfos.unit = unit;
          setRequestInfo(requestInfos);
          setUpdate(!update);
        }
      });
    }
  }

  const popUpToggle = (a) => {
    if(a == 'show') {
      $("div#modal").css('display', 'block');
      $("div#modal input#price").val(requestInfo.amount);
      $("div#modal option[value='" + requestInfo.unit + "']").attr('selected', true);
    }
    if(a == 'hide')
    $("div#modal").css('display', 'none');
  }

  const confirmToggle = (a) => {
    switch (a) {
      case 'hide':
        $("div#confirmModal").hide();
        break;

      case 'deposit':
        $('div.depositConfirm').show();
        break;
    
      default:
        break;
    }
  }

  console.log(isWaiting);
  if(isWaiting) {
    return (
      <div className="max-w-sm mx-auto py-10 text-center">
        <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
      </div>
    )
  } else {
    var containerHeight = innerHeight - 315;
    console.log($('main').css('width'));
    var messengerWidth = $('main').css('width').slice(0, -2) - 110;
    console.log(requestInfo.images);
    var datetime = new Date(requestInfo.created_at);
    if(datetime.getHours() >= 12){
      var time = datetime.getHours() - 12 + ":" + datetime.getMinutes() + " PM";
    } else {
      var time = datetime.getHours() + ":" + datetime.getMinutes() + " AM";
    }
    var month = constant.month[datetime.getMonth()];
    var day = datetime.getDate();
    datetime = time + ', ' + month + ' ' + day;
    if (showPayment) {
      return (
        <BrowserRouter>
          <Elements stripe={loadStripe('pk_test_51HtrYKJyHziuhAX0GAQs9a6fajsFjcQanWHSmb384TC5aJLZdsPv4oCRAbUJ20kHozUSmkACPtk6abdlWzICm6k600VHofe1zg')}>
            <ElementDemos
              requestID = {props.requestID}
              afterDeposit = {() => props.afterDeposit()}
            />
          </Elements>
        </BrowserRouter>
          );
    } else {
      return (
        <div>
          <div id="modal" className="h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
              <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
                <div className="rounded-t-xl h-10 pt-1" style={{ background:'linear-gradient(to right, RGB(5,235,189), RGB(19,120,212))' }}>
                  <p className="text-md md:text-lg text-center text-white font-bold leading-10">Update Offer</p>
                  <a className="block h-6 w-6 absolute -top-2 -right-2 rounded-full bg-white text-center" onClick={() => popUpToggle('hide')} style={{ boxShadow:'0 0 8px #353535' }}>
                    <span className="leading-6"><i className="fas fa-times"></i></span>
                  </a>
                </div>

                <div className="w-11/12 mx-auto grid grid-cols-2 gap-x-4">
                  <div className="col-span-1">
                    <label htmlFor="price" className="block text-xs md:text-sm font-medium text-gray-700 mt-4">Project Amount<sup style={{color:'red'}}>*</sup>
                    </label>
                    <input type="number" value={price} id="price" className="w-full rounded-lg text-xs md:text-sm bg-gray-200 text-gray-500 border-none" onChange={handlePriceChange} />
                  </div>
                  <div className="col-span-1">
                    <label htmlFor="price" className="block text-xs md:text-sm font-medium text-gray-700 mt-4">Currency<sup style={{color:'red'}}>*</sup>
                    </label>
                    <select value={currency} id="currency" className="w-full rounded-lg text-xs md:text-sm bg-gray-200 text-gray-500 border-none" onChange={handleCurrencyChange}>
                      <option value="gbp">GBP</option>
                      <option value="usd">USD</option>
                      <option value="eur">EUR</option>
                    </select>
                  </div>
                </div>
                <div className="w-11/12 mx-auto mt-4">
                  <button className="block mx-auto px-4 py-2 rounded-lg text-white text-sm md:text-md font-semibold" style={{ background:'rgb(88,183,189)' }} onClick={updateOffer}>
                    Update
                  </button>
                </div>
              </div>
          </div>

          <div id="confirmModal" className="depositConfirm h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
              <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
              <div className="w-8/12 mx-auto h-26 mt-4">
                  <p className="text-center text-lg md:text-xl font-bold">Are you sure?</p>
                  <p className="text-center text-md md:text-lg text-gray-700 mt-3 mb-5">Do you really want to create deposit for this request?</p>
                </div>
                <div className="w-full h-16" id="confirmBtn">
                  <div className="w-full grid grid-cols-2 h-full">
                    <div className="col-span-1 h-full">
                      <button className="w-full h-full block mx-auto px-4 py-1 rounded-bl-lg text-gray-500  text-md md:text-lg bg-white" onClick={() => confirmToggle('hide')}>Cancel</button>
                    </div>
                    <div className="col-span-1">
                      <button className="w-full h-full block mx-auto px-4 py-1 rounded-br-lg text-white font-bold text-md md:text-lg" style={{ background:'rgb(88,183,189)' }} onClick={createDeposit}>Yes</button>
                    </div>
                  </div>
                </div>
              </div>
          </div>

          <div className="w-full bg-white" style={{height:'70px', paddingTop:'10px'}}>
            <div style={{float:'left', marginLeft:'15px'}}>
              <a className="text-center text-gray-500" onClick={()=> props.back()} style={{lineHeight:'50px'}}>
                <i className="fas fa-chevron-left"></i>
              </a>
            </div>
            <div className="float-left ml-4">
              <img src={constant.baseURL + 'img/profile-image/' + contactInfo.avatar + '.jpg'} alt={contactInfo.avatar} className="rounded-full" style={{ width:'50px', height:'50px' }}/>
            </div>
            <p className="float-left text-lg md:text-xl text-center text-gray-500 font-bold ml-4">{contactInfo.name} <br/>
            <span className="text-sm md:text-md font-normal">
              Last seen 5 min ago
            </span>
            </p>
          </div>
          <div className="bg-gray-100 pt-2">
            <div className="relative">
              <div id="requestdetail absolute top-1">
                <p className="text-center text-xs md:text-sm text-gray-400">{ datetime }</p>
                <div className="w-10/12 mx-auto bg-white py-1 py-2">
                  <p className="text-center text-gray-700 text-sm md:text-md">
                    { requestInfo.content }
                  </p>
                  <p className="text-gray-500 text-xs md:text-sm ml-2">
                    Offer: <span className="text-gray-600">{ requestInfo.amount + requestInfo.unit.toUpperCase() }</span>
                  </p>
                  <div id="reqeustImages" className="mt-2">
                    {
                      requestInfo.images.map((requestInfo, key) => {
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
              <div id="chatContainer" style={{height:containerHeight+'px', overflow:'auto'}}>
                <div id="requestChatContainer">
                {
                  requestChats.map((chat, i)=>{
                    var datetime = new Date(chat.created_at);
                    if(datetime.getHours() >= 12){
                      var time = datetime.getHours() - 12 + ":" + datetime.getMinutes() + " PM";
                    } else {
                      var time = datetime.getHours() + ":" + datetime.getMinutes() + " AM";
                    }
                    var month = constant.month[datetime.getMonth()];
                    var day = datetime.getDate();
                    datetime = time + ', ' + month + ' ' + day;
                    var isUser = (chat.send_id == accountInfo.id) ? true : false;
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
                  <div id="buttons" className="mt-16">
                    {
                      (accountInfo.accountType == 'influencer')
                      ?
                      <div></div>
                      :
                      <div className="w-full">
                        {
                          (requestInfo.status == 1)
                          ?
                          <div className="flex justify-evenly">
                            <div>
                              <button className="mx-auto px-3 py-2 rounded-sm text-white text-sm md:text-md font-semibold " style={{ background:'rgb(88,183,189)' }} onClick={() => confirmToggle('deposit')}>Add Deposit</button>
                            </div>
                            <div>
                              <button className="mx-auto px-3 py-2 rounded-sm text-white text-sm md:text-md font-semibold " style={{ background:'rgb(88,183,189)' }} onClick={() => popUpToggle('show')}>Update offer</button>
                            </div>
                          </div>
                          :
                            <button className="block mx-auto px-4 py-1 rounded-sm text-white text-sm md:text-md bg-gray-500" disabled>Deposit Made</button>
                        }
                      </div>
                    }
                  </div>
                  <div className="h-40"></div>
                </div>
              </div>
            </div>
          </div>
          <div className="w-full md:max-w-7xl fixed bottom-0">
            <div className="w-full bg-white" style={{height:'60px', borderTop:'1px solid lightgray'}}>
              <div className="float-right">
                <a onClick={sendMessage} style={{display:'block',height:'60px', width:'60px', background:'rgb(88,183,189)', fontSize:'20px', lineHeight:'60px', color:'white', textAlign:'center'}}>
                  <i className="fas fa-paper-plane"></i>
                </a>
              </div>
              <div className="float-left">
                <a href="#" style={{fontSize:'20px', lineHeight:'60px', padding:'0 10px'}} className="text-gray-400">
                  <i className="fas fa-paperclip"></i>
                </a>                  
              </div>
              <div>
                <input type="text" value={message} id="message" className="w-full border-none" autoComplete="off" placeholder="Type your message ..." onChange={handleMessageChange} style={{width:messengerWidth+'px', margin:'10px 0'}}/>
              </div>
              <div className="clearfix"></div>
            </div>
          </div>
        </div>
      );
    }
  }
}

export default RequestDetailComponent;