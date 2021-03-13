import React, { Component } from 'react';
import API from '../api';
import constant from '../const';
import $, { error } from 'jquery';

export class RequestComponent extends Component {
  constructor() {
    super();
    this.state = {
      requests:[],
      isWaiting: true,
    };
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
        console.log(response.data.data);
        requests = response.data.data;
        this.setState({requests});
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
      const requests = this1.state.requests;
      requests.push(data.data);
      console.log(requests);
      this1.setState({
        requests:requests,
      });
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
          <div className="max-w-sm mx-auto text-center py-10">
            <p className="text-center">
              No request to show
            </p>
          </div>
        )
      } else {
        return (
          <div className="mt-10 mx-3 rounded" style={{boxShadow:'#eee 0 0 10px 0'}}>
            {
              this.state.requests.map((request, i)=>{
                return(
                  <div key={i} className="w-11/12 mx-auto">
                    <div className="pt-8">
                      <img src={ constant.baseURL + 'img/avatar-image/' + request.accountInfo[0].avatar + '.jpg' } alt={ request.accountInfo[0].avatar } className="rounded-full" style={{width:'90px', height:'90px', float:'left'}}/>
                      <div style={{marginLeft:'105px'}}>
                        <p className="text-lg md:text-xl font-bold">
                          { request.accountInfo[0].name }
                        </p>
                      </div>
                      <div style={{margin:'3px 0 3px 105px'}}>
                        <p className="text-md md:text-lg text-gray-500 overflow-hidden" style={{height:'25px'}}>
                          { request.requestContent.content }
                        </p>
                      </div>
                      <div style={{marginLeft:'105px'}}>
                        <p className="text-md md:text-lg text-gray-500">
                          Offer: <span className="text-black font-bold">{request.requestContent.amount + request.requestContent.unit} {}</span>
                        </p>
                      </div>
                      <div className="clearfix"></div>
                    </div>
                    <div className="w-full grid grid-cols-6 gap-x-1 mt-3 mb-4">
                      {
                        request.requestContent.images.map((image, i) =>(
                          <div key={i} className="row-span-1">
                            <img src={constant.baseURL + 'img/task-image/' + image.image + '.jpg'} alt={image.image} className="w-full"/>
                          </div>
                        ))
                      }
                    </div>
                    <div className="w-full">
                      <a className="block rounded-lg text-center text-white w-full py-2 my-3 font-bold" onClick={() => this.onRequestClick(request.id)} style={{background:'rgb(92,180,184)'}}> Read More</a>
                    </div>
                    <hr className="mt-5"/>
                  </div>
                );
              })
            }
          </div>
        );
      }
    }
  }
}