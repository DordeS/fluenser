import React, { useEffect, useState } from 'react';
import API from '../api';
import constant from '../const';
import $ from 'jquery';
import { create } from 'lodash';

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
    var msg = message.replace(/\?/g, '‏‏‎ ‎');
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

  const handleMessageChange = (e) => {
    setMessage(e.target.value);
  }

  const handleMessageClick = () => {
    props.inboxClickEvent(props.inboxID);
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
            </div>
            <div style={{height:containerHeight+'px', overflow:'auto'}} className="bg-gray-100">
              <div id="chatcontainer">
                {
                  chats.map((chat, i)=>{
                    var created_at = chat.created_at;
                    created_at = created_at.replace(/:| |-/g, ',');
                    var datetime = created_at.split(',');
                    if(datetime[3] >= 12){
                      var time = datetime[3] - 12 + ":" + datetime[4] + " PM";
                    } else {
                      var time = datetime[3] + ":" + datetime[4] + " AM";
                    }
                    var month = constant.month[parseInt(datetime[1])];
                    var day = datetime[2];
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
                  <a onClick={sendMessage} style={{display:'block',height:'60px', width:'60px', background:'rgb(10, 192, 198)', fontSize:'20px', lineHeight:'60px', color:'white'}}>
                    <i className="fas fa-paper-plane"></i>
                  </a>
                </div>
                <div className="float-left">
                  <a href="#" style={{fontSize:'20px', lineHeight:'60px', padding:'0 10px'}} className="text-gray-400">
                    <i className="fas fa-paperclip"></i>
                  </a>
                </div>
                <div>
                  <input type="text" value={ message } id="message" className="w-full border-none" autoComplete="off" placeholder="Write a Message ..." onChange={handleMessageChange} style={{width:messengerWidth+'px', margin:'10px 0'}} onClick={handleMessageClick}/>
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