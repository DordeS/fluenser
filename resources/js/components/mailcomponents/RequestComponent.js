import React, { Component } from 'react';
import API from '../api';
import constant from '../const';
import $, { error } from 'jquery';

export class RequestComponent extends Component {
  constructor() {
    super();
    this.state = {
      user_id: 0,
      requests:[],
      showRequests:[],
      isWaiting: true,
    };
    this.onSearch = this.onSearch.bind(this);
  }

  onSearch(e) {
    e.preventDefault();
    var keyword = this.requestSearch.value;
    console.log(keyword);
    var requests = this.state.requests;
    var newRequests = [];
    requests.map((request, i) => {
      console.log(request.accountInfo[0].name.toUpperCase().search(keyword.toUpperCase()));
      if(request.accountInfo[0].name.toUpperCase().search(keyword.toUpperCase()) != -1) {
        newRequests.push(request);
      }
    });
    this.setState({showRequests: newRequests});
  }


  onRequestClick(requestID) {
    this.props.requestClickEvent(requestID);
  }

  componentDidMount()
  {
    // request
    const headers ={
      'Accept': 'application/json'
    };
    var requests;
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('request?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      this.setState({ isWaiting: false });
      if(response.status == 200) {
        console.log('-------------');
        console.log(response.data);
        requests = response.data.data;
        this.setState({requests, showRequests:requests, user_id:response.data.user_id});
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
      console.log('pusher_data');
      if(data.trigger == 'request') {
        console.log(data.influencer_id);
        if(data.influencer_id == this1.state.user_id) {
          console.log(data.request);
          var requests = this1.state.requests;
          requests.unshift(data.request);
          console.log(requests);
          this1.setState({requests});
        }
      }
    });
  }
  
  render() {
    if(this.state.isWaiting) {
      return (
        <div className="max-w-sm mx-auto py-10 text-center">
          <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
        </div>
      )
    } else {
      if (this.state.requests.length == 0) {
        return (
          <div>
           <div id="requestSearch">
              <div className="py-2 w-11/12 mx-auto mt-4 relative">
                <input type="text" name="requestSearch" id="requestSearch" style={{ height:'45px',  }} className="w-full px-6 py-1 rounded-full bg-gray-100 border-none" ref={(e) => this.requestSearch = e} placeholder="Search here"/>
                <button className="absolute right-4 text-gray-500" style={{height:'45px'}} onClick={this.onSearch}>
                  <i className="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div className="max-w-sm mx-auto text-center py-10">
              <p className="text-center">
                No request to show
              </p>
            </div>
          </div>
        )
      } else {
        var containerHeight = innerHeight - 214;
        return (
          <div>
            <div id="requestSearch">
              <div className="py-2 w-11/12 mx-auto mt-4 relative">
                <input type="text" name="requestSearch" id="requestSearch" style={{ height:'45px',  }} className="w-full px-6 py-1 rounded-full bg-gray-100 border-none" ref={(e) => this.requestSearch = e} placeholder="Search here"/>
                <button className="absolute right-4 text-gray-500" style={{height:'45px'}} onClick={this.onSearch}>
                  <i className="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div className="pt-2 mt-8 w-11/12 mx-auto rounded" style={{boxShadow:'0 0 3px 3px #eee'}}>
              <div style={{ height:containerHeight, overflow:'auto'}}>
                {
                  this.state.showRequests.map((request, i)=>{
                    return(
                      <div key={i} className="w-11/12 mx-auto">
                        <div className='pt-5'>
                          <img src={ constant.baseURL + 'img/avatar-image/' + request.accountInfo[0].avatar + '.jpg' } alt={ request.accountInfo[0].avatar } className="rounded-full" style={{width:'55px', height:'55px', float:'left'}}/>
                          <div style={{marginLeft:'70px'}}>
                            <p className="text-sm md:text-md font-bold">
                              { request.accountInfo[0].name }
                            </p>
                          </div>
                          <div style={{margin:'0 0 0 70px'}}>
                            <p className="text-xs md:text-sm text-gray-500 overflow-hidden" style={{height:'17px'}}>
                              { request.requestContent.content }
                            </p>
                          </div>
                          <div style={{marginLeft:'70px'}}>
                            <p className="text-xs md:text-sm text-gray-500">
                              Offer: <span className="text-black font-bold">{request.requestContent.amount + ' ' + request.requestContent.unit.toUpperCase()} {}</span>
                            </p>
                          </div>
                          <div className="clearfix"></div>
                        </div>
                        <div className="w-full mt-3 mb-4">
                          {
                            request.requestContent.images.map((image, i) =>(
                              <div key={i} className="float-left ml-2" style={{ width:'40px', height:'40px' }}>
                                <img src={constant.baseURL + 'img/task-image/' + image.image + '.jpg'} alt={image.image} className="w-full"/>
                              </div>
                            ))
                          }
                        </div>
                        <div className="clearfix"></div>
                        <div className="w-full">
                          <a className="block rounded-md text-center text-white w-full py-2 my-3 font-bold text-xs md:text-sm" onClick={() => this.onRequestClick(request.id)} style={{background:'#119dac'}}> Read More</a>
                        </div>
                        <hr className="mt-5"/>
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