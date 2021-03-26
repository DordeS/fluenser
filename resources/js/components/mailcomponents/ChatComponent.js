import React, { Component } from 'react';
import API from '../api';
import constant from '../const';
import $ from 'jquery';

export class ChatComponent extends Component {
  constructor() {
    super();
    this.state = {
      chats: [],
      contactName: '',
      userID: 0,
      isWaiting: true,
      message: '',
      api_token: '',
      contactInfo: [],
      contactID: 0,
      count: 0,
    };
    this.sendMessage = this.sendMessage.bind(this);
  }

  sendMessage(e) {
    e.preventDefault();
    var message = this.message.value;
    console.log(message);
    API.get('sendMessage/' + this.state.chats[0].inbox_id + '/' + message + '?api_token=' + this.state.api_token, {
      headers : {
        "Accept": 'application/json'
      }
    }).then((res) => {
      if(res.status == 200) {
        console.log(res.data.data);
      }
    }).catch(error => {
      console.log(error);
    });
  }

  componentDidMount()
  {
    // request
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('chat/' + this.props.inboxID + '?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      this.setState({ isWaiting: false });
      if(response.status == 200) {
        console.log('-------------');
        console.log(response.data);
        var chats = response.data.data.chatInfo;
        var contactName = response.data.data.name;
        var userID = response.data.data.userID;
        var contactInfo = response.data.contactInfo[0];
        var contactID = response.data.contactID;
        var count = this.state.count ++;
        this.setState({chats, contactName, userID, api_token, contactInfo, contactID, count});
      }
    }).catch(error => {
      console.log(error);
    });
    console.log("mounted component message");

    // Pusher
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
  
    var pusher = new Pusher('da7cd3b12e18c9e2e461', {
      cluster: 'eu',
    });
    const this1 = this
    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', (data) => {
      console.log('qwer');
      console.log(data);

      if(data.trigger == 'chat') {
        if(this.state.userID == data.inboxInfo.send_id &&
            this.state.contactID == data.inboxInfo.receive_id ||
            this.state.userID == data.inboxInfo.receive_id &&
            this.state.contactID == data.inboxInfo.send_id){
          const chats = this1.state.chats;
          chats.push(data.inboxInfo);
          console.log(chats);
          this1.setState({chats:chats});
        }
      }
    });
  }

  componentDidUpdate() {
    var element = document.getElementById('chatcontainer');
    console.log("+++++++");
    console.log(element);
    if(element != null) {
      element.scrollIntoView(false);
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
      if (this.state.chats.length == 0) {
        return (
          <div className="max-w-md md:max-w-xl mx-auto text-center pb-10">
            <div className="w-full grid grid-cols-12 gap-x-2">
              <div className="col-span-1">
                <a className="text-center text-green-500" onClick={()=>back()}>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
                  </svg>
                </a>
              </div>
              <div className="col-span-11">
                <p className="text-center text-lg md:text-xl pb-3 font-bold">
                  {this.state.contactName}
                </p>
              </div>
            </div>
            <p className="text-center">
              No message to show
            </p>
          </div>
        )
      } else {
        var containerHeight = innerHeight - 225;
        console.log($('main').css('width'));
        var messengerWidth = $('main').css('width').slice(0, -2) - 110;
        return (
          <div className="w-full text-center">
            <div className="w-full" style={{height:'70px'}}>
              <div style={{float:'left', marginLeft:'15px'}}>
                <a className="text-center text-gray-500" onClick={()=> this.props.back()} style={{lineHeight:'70px'}}>
                  <i className="fas fa-chevron-left"></i>
                </a>
              </div>
              <div className="float-left" style={{width:'50px', height:'50px', margin:'10px 0', marginLeft:'28px'}}>
                <img src={constant.baseURL + 'img/avatar-image/' + this.state.contactInfo.avatar + '.jpg'} alt={this.state.contactInfo.avatar} className="rounded-full"/>
              </div>
              <div className="float-left" style={{marginLeft:'12px'}}>
                <p className="text-center text-md md:text-xl pt-2 text-gray-700 font-bold" style={{lineHeight:'50px'}}>
                  {this.state.contactName}
                </p>
              </div>
              <button className="float-right bg-white rounded-xl" style={{marginRight:'15px', height:'35px', marginTop:'10px', boxShadow:'0 0 8px 0 #999'}}> <p style={{lineHeight:'35px'}} className="px-3 text-sm text-gray-500">Release <span className="font-bold">45.00GBP</span></p></button>
            </div>
            <div style={{height:containerHeight+'px', overflow:'auto'}} className="bg-gray-100">
              <div id="chatcontainer">
                {
                  this.state.chats.map((chat, i)=>{
                    var datetime = new Date(chat.created_at);
                    if(datetime.getHours() >= 12){
                      var time = datetime.getHours() - 12 + ":" + datetime.getMinutes() + " PM";
                    } else {
                      var time = datetime.getHours() + ":" + datetime.getMinutes() + " AM";
                    }
                    var month = constant.month[datetime.getMonth()];
                    var day = datetime.getDate();
                    datetime = time + ', ' + month + ' ' + day;
                    var isUser = (chat.send_id == this.state.userID) ? true : false;
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
            <div className="w-full md:max-w-7xl fixed" style={{bottom:'55px'}}>
              <div className="w-full bg-white" style={{height:'60px', borderTop:'1px solid lightgray'}}>
                <div className="float-right">
                  <a onClick={this.sendMessage} style={{display:'block',height:'60px', width:'60px', background:'rgb(88,183,189)', fontSize:'20px', lineHeight:'60px', color:'white'}}>
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