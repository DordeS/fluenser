import React, { useEffect, useState } from 'react';
import API from '../api';
import constant from '../const';
import $ from 'jquery';

const ChatComponent = (props) => {
  const [chats, setChats] = useState([]);
  const [contactName, setContactName] = useState('');
  const [userID, setUserID] = useState(0);
  const [isWaiting, setIsWaiting] = useState(true);
  const [message, setMessage] = useState('');
  const [contactInfo, setContactInfo] = useState({});
  const [contactID, setContactID] = useState(0);
  const [requestInfo, setRequestInfo] = useState({});
  const [update, setUpdate] = useState(false);
  
  const sendMessage = (e) => {
    e.preventDefault();
    var msg = message;
    console.log(msg);
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('sendMessage/' + chats[0].inbox_id + '/' + msg + '?api_token=' + api_token, {
      headers : {
        "Accept": 'application/json'
      }
    }).then((res) => {
      if(res.status == 200) {
        setMessage('');
        console.log(res.data.data);
      }
    }).catch(error => {
      console.log(error);
    });
  }

  const handleMessageClick = (e) => {
    setMessage(e.target.value);
  }

  const releaseDeposit = () => {
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    console.log(requestInfo.id);

    API.get('releaseDeposit/' + requestInfo.id + '?api_token=' + api_token, {
      headers: headers,
    }
    ).then((res) => {
      if(res.status == 200) {
        console.log(res.status);
        confirmToggle('hide');
      }
    }).catch(err=>{console.log(err);});
  }

  const confirmToggle = (a) => {
    switch (a) {
      case 'hide':
        $("div#confirmModal").hide();
        break;
      case 'release':
        $('div.releaseConfirm').show();
        break;
      default:
        break;
    }
  }


  useEffect(() => {
    $("nav").hide();
    let isMount = false;
    // request
    const headers ={
      'Accept': 'application/json'
    };
    
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('chat/' + props.inboxID + '?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      if(!isMount){
        setIsWaiting(false);
        if(response.status == 200) {
          console.log('-------------');
          console.log(response.data);
          console.log(response.data.data.userID);
          var chat = response.data.data.chatInfo;
          var contact_Name = response.data.data.name;
          var user_ID = response.data.data.userID;
          var contact_Info = response.data.contactInfo[0];
          var contact_ID = response.data.contactID;
          var requestInfo = response.data.data.requestInfo;
          setChats(chat);
          setContactName(contact_Name);
          setUserID(user_ID);
          setContactInfo(contact_Info);
          setContactID(contact_ID);
          setRequestInfo(requestInfo);
        }
      }
    }).catch(error => {
      console.log(error);
    });

    // Pusher
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
  
    var pusher = new Pusher('da7cd3b12e18c9e2e461', {
      cluster: 'eu',
    });
    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', (data) => {
      console.log('qwer');
      console.log(data);

      if(data.trigger == 'chat') {
        console.log(data.inboxInfo.send_id);
        console.log(data.inboxInfo.receive_id);

        if(!isMount){
          if(userID == data.inboxInfo.send_id &&
            contactID == data.inboxInfo.receive_id || 
            userID == data.inboxInfo.receive_id &&
              contactID == data.inboxInfo.send_id){
              const chat = chats;
              chat.push(data.inboxInfo);
              setChats(chat);
              setUpdate(!update);
            }
        }
      }

      if(data.trigger == 'request_status'){
        if(data.request_id = requestInfo.id) {
          var requestInfos = requestInfo;
          requestInfos.status = data.status;
          setRequestInfo(requestInfos);
          setUpdate(!update);
        }
      }
    });

    var element = document.getElementById('chatcontainer');
    if(element != null) {
      console.log('+++++++++++');
      element.scrollIntoView(false);
    }

    return () => {
      isMount = true;
    };
  }, [userID, contactID, requestInfo.status, update] );

    if(isWaiting) {
      return (
        <div className="max-w-sm mx-auto py-10 text-center">
          <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
        </div>
      )
    } else {
      if (chats.length == 0) {
        return (
          <div className="max-w-md md:max-w-xl mx-auto text-center pb-10">
            <div className="w-full" style={{height:'70px'}}>
              <div style={{float:'left', marginLeft:'15px'}}>
                <a className="text-center text-gray-500" onClick={()=> props.back()} style={{lineHeight:'70px'}}>
                  <i className="fas fa-chevron-left"></i>
                </a>
              </div>
              <div className="float-left" style={{width:'50px', height:'50px', margin:'10px 0', marginLeft:'28px'}}>
                <img src={constant.baseURL + 'img/profile-image/' + contactInfo.avatar + '.jpg'} alt={contactInfo.avatar} className="rounded-full"/>
              </div>
              <div className="float-left" style={{marginLeft:'12px'}}>
                <p className="text-center text-md md:text-xl pt-2 text-gray-700 font-bold" style={{lineHeight:'50px'}}>
                  {contactName}
                </p>
              </div>
            </div>
            <p className="text-center">
              No message to show
            </p>
          </div>
        )
      } else {
        var containerHeight = innerHeight - 170;
        console.log($('main').css('width'));
        var messengerWidth = $('main').css('width').slice(0, -2) - 110;
        var currency = requestInfo.unit;
        console.log("asaaaaaaaaaaaaaaaaaa");
        console.log(currency);
        if(currency != undefined)
          currency = currency.toUpperCase();
        return (
          <div className="w-full text-center">

            <div id="confirmModal" className="releaseConfirm h-screen w-screen bg-black bg-opacity-70 fixed top-0 z-50 hidden">
              <div className="w-11/12 h-48 bg-white absolute rounded-xl" style={{ top:'50%', marginTop:'-6rem', left:'50%', marginLeft:'-45.83333%' }}>
                <div className="w-8/12 mx-auto h-26 mt-4">
                  <p className="text-center text-lg md:text-xl font-bold">Are you sure?</p>
                  <p className="text-center text-md md:text-lg text-gray-700 mt-3 mb-5">Do you really want to release?</p>
                </div>
                <div className="w-full h-16" id="confirmBtn">
                  <div className="w-full grid grid-cols-2 h-full">
                    <div className="col-span-1 h-full">
                      <button className="w-full h-full block mx-auto px-4 py-1 rounded-bl-lg text-gray-500  text-md md:text-lg bg-white" onClick={() => confirmToggle('hide')}>Cancel</button>
                    </div>
                    <div className="col-span-1">
                      <button className="w-full h-full block mx-auto px-4 py-1 rounded-br-lg text-white font-bold text-md md:text-lg" style={{ background:'rgb(88,183,189)' }} onClick={() => releaseDeposit()}>Yes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="w-full flex justify-between" style={{height:'70px'}}>
              <div style={{float:'left', marginLeft:'15px'}} className="flex-shrink-0">
                <a className="text-center float-left text-gray-500" onClick={()=> props.back()} style={{lineHeight:'70px'}}>
                  <i className="fas fa-chevron-left"></i>
                </a>
                <div className="float-left flex-shrink-0" style={{width:'50px', height:'50px', margin:'10px 0', marginLeft:'28px'}}>
                  <img src={constant.baseURL + 'img/profile-image/' + contactInfo.avatar + '.jpg'} alt={contactInfo.avatar} className="rounded-full"/>
                </div>
                <div className="float-left flex overflow-hidden" style={{marginLeft:'12px'}}>
                  <p className="text-center text-md md:text-xl pt-2 text-gray-700 font-bold" style={{lineHeight:'50px'}}>
                    {contactName}
                  </p>
                </div>
              </div>
                {
                  (contactInfo.accountType == 'brand')
                  ?
                    (requestInfo.status == 3)
                    ?
                    <button className="flex-shrink-0 float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}} onClick={(request_id) => props.leaveReview(requestInfo.id)}>
                      <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">Leave a Review</p>
                    </button>
                    :
                      (requestInfo.status == 4)
                      ?
                      <button className="flex-shrink-0 float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}} disabled>
                        <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">completed</p>
                      </button>
                      :
                      <button className="flex-shrink-0 float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}}>
                        <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">Request to release</p>
                      </button>
                  :
                    (currency == undefined)
                    ?
                    <p></p>
                    :
                    (requestInfo.status == 3)
                      ?
                      <button className="flex-shrink-0 float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}} onClick={(request_id) => props.leaveReview(requestInfo.id)}>
                      <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">Leave a Review</p>
                      </button>
                      :
                        (requestInfo.status == 4)
                        ?
                        <button className="flex-shrink-0 float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}} disabled>
                        <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">Completed</p>
                        </button>
                        :
                        <button className="flex-shrink-0 float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}} onClick={() => confirmToggle('release')}>
                        <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">Release <span className="font-bold">{ requestInfo.amount + currency }</span></p>
                        </button>
                }
            </div>
            <div style={{height:containerHeight+'px', overflow:'auto'}} className="bg-gray-100">
              <div id="chatcontainer">
                {
                  chats.map((chat, i)=>{
                    var datetime = new Date(chat.created_at);
                    if(datetime.getHours() >= 12){
                      var time = datetime.getHours() - 12 + ":" + datetime.getMinutes() + " PM";
                    } else {
                      var time = datetime.getHours() + ":" + datetime.getMinutes() + " AM";
                    }
                    var month = constant.month[datetime.getMonth()];
                    var day = datetime.getDate();
                    datetime = time + ', ' + month + ' ' + day;
                    var isUser = (chat.send_id == userID) ? true : false;
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
                <div className="h-40"></div>
              </div>
            </div>
            <div className="w-full md:max-w-7xl fixed bottom-0">
              <div className="w-full bg-white" style={{height:'60px', borderTop:'1px solid lightgray'}}>
                <div className="float-right">
                  <a onClick={sendMessage} style={{display:'block',height:'60px', width:'60px', background:'rgb(88,183,189)', fontSize:'20px', lineHeight:'60px', color:'white'}}>
                    <i className="fas fa-paper-plane"></i>
                  </a>
                </div>
                <div className="float-left">
                  <a href="#" style={{fontSize:'20px', lineHeight:'60px', padding:'0 10px'}} className="text-gray-400">
                    <i className="fas fa-paperclip"></i>
                  </a>                  
                </div>
                <div>
                  <input type="text" value={ message } id="message" className="w-full border-none" autoComplete="off" placeholder="Type your message ..." onChange={handleMessageClick} style={{width:messengerWidth+'px', margin:'10px 0'}}/>
                </div>
                <div className="clearfix"></div>
              </div>
            </div>
          </div>
        );
      }
    }
}

export default ChatComponent;