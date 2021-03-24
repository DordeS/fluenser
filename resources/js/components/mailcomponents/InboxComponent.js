import React, { Component } from 'react';
import API from '../api';
import constant from '../const';
import $, { error, map } from 'jquery';

export class InboxComponent extends Component {
  constructor() {
    super();
    this.state = {
      inboxes:[],
      showInboxes:[],
      isWaiting: true,
    };

    this.onSearch = this.onSearch.bind(this);
  }

  onSearch(e) {
    e.preventDefault();
    var keyword = this.inboxSearch.value;
    console.log(keyword);
    var inboxes = this.state.inboxes;
    var newInboxes = [];
    inboxes.map((inbox, i) => {
      console.log(inbox.accountInfo[0].name.toUpperCase().search(keyword.toUpperCase()));
      if(inbox.accountInfo[0].name.toUpperCase().search(keyword.toUpperCase()) != -1) {
        newInboxes.push(inbox);
      }
    });
    this.setState({showInboxes: newInboxes});
  }

  componentDidMount()
  {
    // send request
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('inbox?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      this.setState({ isWaiting: false });
      if(response.status == 200) {
        console.log('-------------');
        console.log(response.data.data);
        var inboxes = response.data.data;
        this.setState({
          inboxes, showInboxes:inboxes
        });
      }
    }).catch(error => {
      console.log(error);
    })
    console.log("mounted component message");

    // Pusher
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
  
    var pusher = new Pusher('da7cd3b12e18c9e2e461', {
      cluster: 'eu',
    });
    const this1 = this
    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', function(data) {
      const inboxes = this1.state.inboxes;
      inboxes.push(data.data);
      console.log(inboxes);
      this1.setState({
        inboxes:inboxes,
        showInboxes:inboxes,
      });
    });
  }

  onInboxClick(inboxID) {
    this.props.inboxClickEvent(inboxID);
  }
  
  render() {
    if(this.state.isWaiting) {
      return (
        <div className="max-w-sm mx-auto py-10 text-center">
          <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
        </div>
      )
    } else {
      if (this.state.showInboxes.length == 0) {
        return (
          <div>
            <div id="inboxSearch">
              <div className="py-2 w-11/12 mx-auto mt-4 relative">
                <input type="text" name="inboxSearch" id="inboxSearch" style={{ height:'45px',  }} className="w-full px-6 py-1 rounded-full bg-gray-100 border-none" ref={(e) => this.inboxSearch = e} placeholder="Search here"/>
                <button className="absolute right-4 text-gray-500" style={{height:'45px'}} onClick={this.onSearch}>
                  <i className="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div className="max-w-sm mx-auto text-center py-10">
              <p className="text-center">
                No inbox to show
              </p>
            </div>
          </div>
        )
      } else {
        var containerHeight = innerHeight - 255;
        return (
          <div>
            <div id="inboxSearch">
              <div className="py-2 w-11/12 mx-auto mt-4 relative">
                <input type="text" name="inboxSearch" id="inboxSearch" style={{ height:'45px',  }} className="w-full px-6 py-1 rounded-full bg-gray-100 border-none" ref={(e) => this.inboxSearch = e} placeholder="Search here"/>
                <button className="absolute right-4 text-gray-500" style={{height:'45px'}} onClick={this.onSearch}>
                  <i className="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div className="pt-6 mt-3 w-11/12 mx-auto rounded" style={{boxShadow:'0 0 3px 3px #eee'}}>
              <div style={{height:containerHeight, overflow:'auto'}}>
                {
                  this.state.showInboxes.map((inbox, i)=>{
                    var time = new Date(inbox.accountInfo[0].updated_at);
                    if(time.getHours() >= 12){
                      time = time.getHours() - 12 + ":" + time.getMinutes() + "PM";
                    } else {
                      time = time.getHours() + ":" + time.getMinutes() + " PM";
                    }
                    return(
                      <div key={i} className="w-11/12 mx-auto rounded px-2">
                        <a href="#" onClick={() => this.onInboxClick(inbox.id)}>
                          <div className="w-full">
                            <div className="w-full">
                              <img src={ constant.baseURL + 'img/avatar-image/' + inbox.accountInfo[0].avatar + '.jpg' } alt={ inbox.accountInfo[0].avatar} className="rounded-full" style={{ width:'55px', height:'55px', float:'left' }}/>
                              <div style={{marginLeft:'75px', paddingTop:'3px'}}>
                                <span className="text-md md:text-lg font-medium text-gray-700">
                                  { inbox.accountInfo[0].name }
                                </span>
                                <span className="text-xs text-gray-400" style={{float:'right'}}>
                                  { time }
                                </span>
                              </div>
                              <div style={{marginLeft:'75px', height:'40px', paddingTop:'3px',paddingBottom:'10px', overflow:'hidden'}}>
                                <p style={{height:'20px', overflow:'hidden'}} className="text-gray-500 text-md">{inbox.inboxContent[0].content}</p>
                              </div>
                            </div>
                          </div>
                        </a>
                        <hr className="pb-3"/>
                      </div>
                    );
                  })
                }
              </div>
            </div>
          </div>
        );
      }
    }
  }
}
