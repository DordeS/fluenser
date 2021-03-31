import React, { Component, useEffect, useState } from 'react';
import API from '../api';
import constant from '../const';
import $ from 'jquery';

const RequestComponent =(props) => {
  const [user_id, setUserID] = useState(0);
  const [requests, setRequests] = useState([]);
  const [showRequests, setShowRequests] = useState([]);
  const [isWaiting, setIsWaiting] = useState(true);
  const [requestSearch, setRequestSearch] = useState("");

  const onSearch = (e) => {
    e.preventDefault();
    var keyword = requestSearch;
    console.log(keyword);
    var tempRequests = requests;
    var newRequests = [];
    tempRequests.map((request, i) => {
      console.log(request.accountInfo[0].name.toUpperCase().search(keyword.toUpperCase()));
      if(request.accountInfo[0].name.toUpperCase().search(keyword.toUpperCase()) != -1) {
        newRequests.push(request);
      }
    });
    setShowRequests(newRequests);
  }

  const handleOnSearchChange = (e) => {
    setRequestSearch(e.target.value);
  }

  useEffect(() => {
    // request
    const headers ={
      'Accept': 'application/json'
    };
    var requests;
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('request?api_token=' + api_token, {
      headers: headers
    }).then((response) => {
      setIsWaiting(false);
      if(response.status == 200) {
        console.log('-------------');
        console.log(response.data);
        requests = response.data.data;
        setRequests(requests);
        setShowRequests(requests);
        setUserID(response.data.user_id);
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

    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', function(data) {
      console.log('pusher_data');
      if(data.trigger == 'request') {
        console.log(data.influencer_id);
        if(data.influencer_id == user_id) {
          console.log(data.request);
          var requests = requests;
          requests.unshift(data.request);
          console.log(requests);
          setRequests(requests);
        }
      }
    });

    props.selectTab('requests');
  }, []);
  
  if(isWaiting) {
    return (
      <div className="max-w-sm mx-auto py-10 text-center">
        <img src={constant.baseURL + 'img/waiting.gif'} alt="waiting" className="mx-auto"/>
      </div>
    )
  } else {
    var containerHeight = innerHeight - 255;
    return (
      <div>
        <div id="requestSearch">
          <div className="py-2 w-11/12 mx-auto mt-3 relative">
            <input type="text" value={requestSearch} id="requestSearch" style={{ height:'45px',  }} className="w-full px-6 py-1 rounded-full bg-gray-100 border-none" onChange={handleOnSearchChange} placeholder="Search here..."/>
            <button className="absolute right-4 text-gray-500" style={{height:'45px'}} onClick={onSearch}>
              <i className="fas fa-search"></i>
            </button>
          </div>
        </div>
        {
          (requests.length == 0)
          ?
          <div className="max-w-sm mx-auto text-center py-10">
            <p className="text-center">
              No request to show
            </p>
          </div>
          :
          <div className="pt-2 mt-8 w-11/12 mx-auto rounded" style={{boxShadow:'0 0 3px 3px #eee'}}>
          <div style={{ height:containerHeight, overflow:'auto'}}>
            {
              showRequests.map((request, i)=>{
                return(
                  <div key={i} className="w-11/12 mx-auto">
                    <div className='pt-5'>
                      <img src={ constant.baseURL + 'img/profile-image/' + request.accountInfo[0].avatar + '.jpg' } alt={ request.accountInfo[0].avatar } className="rounded-full" style={{width:'55px', height:'55px', float:'left'}}/>
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
                      <a className="block rounded-md text-center text-white w-full py-2 my-3 font-bold text-xs md:text-sm" onClick={() => props.onRequestClick(request.id)} style={{background:'#119dac'}}> Read More</a>
                    </div>
                    <hr className="mt-5"/>
                  </div>
                );
              })
            }
          </div>
        </div>
        }
      </div>
    )
  }
}

export default RequestComponent;