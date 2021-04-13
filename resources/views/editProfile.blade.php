@extends('layouts.app')
@section('content')
<header class="bg-white">
  <div class="w-full md:max-w-7xl mx-auto py-1 px-3 sm:px-6 lg:px-8 bg-gray-800 h-10">
    <p class="italic text-lg md:text-xl text-white font-bold leading-8" style="font-family: 'Josefin Sans', sans-serif;">{{ __('EDIT PROFILE') }}</p>
  </div>  
</header>
<main>
  <input type="file" name="image" id="hidden-input" hidden>
  <div class="w-full md:max-w-7xl mx-auto mb-12">
  <form method="post" action={{route('updateProfile', ['user_id' => $accountInfo->id])}}>
    {{ csrf_field() }}
    <!-- Replace with your content -->
      <div class="bg-white">
        <div class="w-full">
          <div class="relative overflow-hidden">
            <a class="block absolute top-4 right-2 rounded-full h-8 w-8 bg-white text-center" style="box-shadow: 0 0 15px #999" onclick="editImg('top')">
              <p class="leading-8 text-lg" style="color: #4addc4">
                <i class="fas fa-pencil-alt"></i>
              </p>
            </a>
            <img src={{ asset('img/profile-image/'.$profile->top_img.'.jpg') }} alt={{ $profile->top_img }} class="w-full" id="top-image">
          </div>
        </div>
        <div class="w-11/12 mx-auto relative -top-32 bg-white rounded-lg pb-3 pt-1" style="box-shadow: 0 0 10px 0 #999">
          <div class="w-1/3 absolute py-1 px-1 bg-white rounded-full" style="top:0; left:50%; transform: translate(-50%, -60%);box-shadow:0 0 8px #333">
            <img class="rounded-full w-full" id="round-image" src={{ asset('img/profile-image/'.$profile->round_img.'.jpg') }} alt={{$profile->round_img}}>
            <a class="absolute block w-8 h-8 bg-white rounded-full text-center" style="right:5%; bottom:5%;box-shadow: 0 0 15px #999" onclick="editImg('round')">
              <p class="leading-8 text-lg" style="color: #4addc4">
                <i class="fas fa-camera"></i>
              </p>
            </a>
          </div>
          <div class="w-11/12 mx-auto" style="margin-top:15vw">
            <label for="name" class="text-gray-500 text-sm md:text-md">Name</label>
            <input type="text" name="name" id="name" style="box-shadow:0 0 10px 0 gray" class="block w-full border-none rounded text-gray-700 font-semibold mb-3 h-10 shadow-inner @error('name') is-invalid @enderror" value="{{ $accountInfo->name }}">

            <label for="username" class="text-gray-500 text-sm md:text-md">Username</label>
            <input type="text" name="username" id="username" style="box-shadow:0 0 10px 0 gray" class="block w-full border-none rounded text-gray-700 font-semibold mb-3 h-10 shadow-inner @error('username') is-invalid @enderror" value="{{ $accountInfo->username }}">

            <label for="state" class="text-gray-500 text-sm md:text-md">City</label>
            <input type="text" name="state" id="state" style="box-shadow:0 0 10px 0 gray" class="block w-full border-none rounded text-gray-700 font-semibold mb-3 h-10 shadow-inner @error('state') is-invalid @enderror" value="{{ $accountInfo->state }}">

            <label for="country" class="text-gray-500 text-sm md:text-md">Country</label>
            <select name="country" id="country" style="box-shadow:0 0 10px 0 gray" class="block w-full border-none rounded text-gray-700 font-semibold mb-3 h-10 shadow-inner @error('state') is-invalid @enderror">
              @foreach ($countries as $country)
                @if (strtoupper(trim($country->name)) == strtoupper(trim($accountInfo->country)))
                  <option value={{ $country->id }} selected>{{ $country->name }}</option>
                @else
                  <option value={{ $country->id }}>{{ $country->name }}</option>
                @endif
              @endforeach
            </select>

            <label for="introduction" class="text-gray-500 text-sm md:text-md">Bio</label>
            <textarea type="text" style="resize: none;" name="introduction" id="introduction" class="bg-gray-100 block w-full border-none rounded h-28 text-gray-700 font-semibold mb-3 h-10 shadow-inner @error('introduction') is-invalid @enderror">{{ $profile->introduction }}</textarea>

            <label for="portfolio" class="text-gray-500 text-sm md:text-md">Add Your Portfolio Images</label>
            <div class="bg-gray-100 block w-full border-none rounded text-gray-700 font-semibold mb-3 shadow-inner px-2 py-1 relative" style="min-height: 4rem">
              <div class="float-right w-20 text-center absolute" style="top: 50%; transform:translateY(-50%); right: 10px">
                <a onclick="editImg('portfolio')">
                  <img src="{{ asset("img/add-image.png") }}" alt="add-image" class="w-1/3 mx-auto">
                  <p class="text-xs md:text-sm text-gray-500">Add Image</p>
                </a>
              </div>
              <div id="portfolio-gallery" class="mr-20">
                @foreach ($portfolios as $portfolio)
                  <div id="gallery-item" class="float-left mr-3 my-2 relative">
                    <img src="{{ asset('img/profile-image/'.$portfolio->slide_img.'.jpg') }}" alt="{{ $portfolio->slide_img }}" class="rounded-sm" style="width: 65px; box-shadow:0 0 5px #333">
                    <a class="block absolute w-5 h-5 text-center rounded-full bg-red-600 text-white text-xs -top-2 -right-2" onclick="removeImage($(this), 'portfolio')"><span class="leading-5">X</span></a>
                  </div>
                @endforeach
              </div>
              <div class="clearfix"></div>
            </div>

            @if ($accountInfo->accountType == 'influencer')
              <label for="partnership" class="text-gray-500 text-sm md:text-md">Add brand logos you have worked with</label>
              <div class="bg-gray-100 block w-full border-none rounded text-gray-700 font-semibold mb-3 shadow-inner px-2 py-1 relative" style="min-height: 3rem">
                <div class="float-right w-20 text-center absolute" style="top: 50%; transform:translateY(-50%); right: 10px">
                  <a onclick="editImg('partnership')">
                    <img src="{{ asset("img/add-image.png") }}" alt="add-image" class="w-1/3 mx-auto">
                    <p class="text-xs md:text-sm text-gray-500">Add Image</p>
                  </a>
                </div>
                <div id="partnership-gallery" class="mr-20">
                  @foreach ($partnerships as $partnership)
                    <div id="gallery-item" class="float-left mr-3 my-2 relative">
                      <img src="{{ asset('img/partnership-image/'.$partnership->partnership_img.'.jpg') }}" alt="{{ $partnership->partnership_img }}" class="rounded-sm" style="width: 65px; box-shadow:0 0 5px #333">
                      <a class="block absolute w-5 h-5 text-center rounded-full bg-red-600 text-white text-xs -top-2 -right-2" onclick="removeImage($(this), 'partnership')"><span class="leading-5">X</span></a>
                    </div>
                  @endforeach
                </div>
                <div class="clearfix"></div>
              </div>                
            @endif

            
            <label for="categories" class="text-gray-500 text-sm md:text-md">Choose 2 categories that describe your content the best</label>
            <div class="bg-gray-100 block w-full border-none rounded text-gray-700 font-semibold shadow-inner px-2 py-1 relative pt-3 mb-3">
              <input type="text" id="categories" class="block w-11/12 mx-auto rounded-full text-gray-700 text-sm md:text-md" style="border: 1px solid lightgray" placeholder="Categories" readonly>
              <div class="w-11/12 mx-auto py-2 px-3 my-3 h-24 overflow-auto rounded-xl" style="box-shadow: 0 0 8px 0 #999">
                @foreach ($categories as $category)
                  <?php $a = 0; ?>
                  @foreach ($selectedCategories as $item)
                      @if ($category->category_name == $item->category_name)
                      <?php $a ++; ?>
                      @endif
                  @endforeach
                  @if ($a == 1)
                    <input type="checkbox" class="rounded border-gray-400 bg-gray-100" name="category[]" id="category" value="{{ $category->id }}" checked><label for="category" class="text-gray-700 text-xs md:text-sm">&nbsp;&nbsp;{{ $category->category_name }}</label><br/>  
                  @else
                    <input type="checkbox" class="rounded border-gray-400 bg-gray-100" name="category[]" id="category" value="{{ $category->id }}"><label for="category" class="text-gray-700 text-xs md:text-sm">&nbsp;&nbsp;{{ $category->category_name }}</label><br/>  
                  @endif
                @endforeach
              </div>
            </div>

            <label for="categories" class="text-gray-500 text-sm md:text-md">Add your social media links</label>
            <div class="bg-gray-100 block w-full border-none rounded text-gray-500 font-semibold shadow-inner px-2 py-1 relative pt-3 mb-3">
              <div class="w-11/12 mx-auto grid grid-cols-12 mb-3">
                <div class="col-span-2 md:col-span-1">
                  <input type="checkbox" name="instagram" id="instagramCheck" class="rounded w-4 h-4 mx-auto my-3 bg-gray-100 border-gray-400" @if ($profile->instagram != '') checked @endif>
                </div>
                <div class="col-span-10 md:col-span-11">
                  <input type="text" name="instagram-link" id="instagram-link" class="text-gray-700 rounded w-full my-2 border-none text-sm md:text-md py-1 px-2" style="box-shadow: 0 0 8px 0 #999" placeholder="Instagram.com/username" value="{{$profile->instagram}}">
                  <select name="instagram-follow" id="instagram-follow" class="text-gray-700 rounded-full w-full my-2 border-none text-sm md:text-md py-1 px-2" style="box-shadow: 0 0 8px 0 #999">
                    <option>How many followers?</option>
                    <option value="11" @if($profile->instagram_follows == 11) selected @endif>1k-10k</option>
                    <option value="60" @if($profile->instagram_follows == 60) selected @endif>10k-50k</option>
                    <option value="600" @if($profile->instagram_follows == 600) selected @endif>100k-500k</option>
                  </select>
                </div>
              </div>
              <div class="w-11/12 mx-auto grid grid-cols-12 mb-3">
                <div class="col-span-2 md:col-span-1">
                  <input type="checkbox" name="youtube" id="youtubeCheck" class="rounded w-4 h-4 mx-auto my-3 bg-gray-100 border-gray-400" @if ($profile->instagram != '') checked @endif>
                </div>
                <div class="col-span-10 md:col-span-11">
                  <input type="text" name="youtube-link" id="youtube-link" class="text-gray-700 rounded w-full my-2 border-none text-sm md:text-md py-1 px-2" style="box-shadow: 0 0 8px 0 #999" placeholder="Youtube.com/username" value="{{$profile->youtube}}">
                  <select name="youtube-follow" id="youtube-follow" class="text-gray-700 rounded-full w-full my-2 border-none text-sm md:text-md py-1 px-2" style="box-shadow: 0 0 8px 0 #999">
                    <option>How many followers?</option>
                    <option value="11" @if($profile->youtube_follows == 11) selected @endif>1k-10k</option>
                    <option value="60" @if($profile->youtube_follows == 60) selected @endif>10k-50k</option>
                    <option value="600" @if($profile->youtube_follows == 600) selected @endif>100k-500k</option>
                  </select>
                </div>
              </div>
              <div class="w-11/12 mx-auto grid grid-cols-12 mb-2">
                <div class="col-span-2 md:col-span-1">
                  <input type="checkbox" name="tiktok" id="tiktokCheck" class="rounded w-4 h-4 mx-auto my-3 bg-gray-100 border-gray-400" @if ($profile->instagram != '') checked @endif>
                </div>
                <div class="col-span-10 md:col-span-11">
                  <input type="text" name="tiktok-link" id="tiktok-link" class="text-gray-700 rounded w-full my-2 border-none text-sm md:text-md py-1 px-2" style="box-shadow: 0 0 8px 0 #999" placeholder="TikTok.com/username" value="{{$profile->tiktok}}">
                  <select name="tiktok-follow" id="tiktok-follow" class="text-gray-700 rounded-full w-full my-2 border-none text-sm md:text-md py-1 px-2" style="box-shadow: 0 0 8px 0 #999">
                    <option>How many followers?</option>
                    <option value="11" @if($profile->tiktok_follows == 11) selected @endif>1k-10k</option>
                    <option value="60" @if($profile->tiktok_follows == 60) selected @endif>10k-50k</option>
                    <option value="600" @if($profile->tiktok_follows == 600) selected @endif>100k-500k</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full">
          <button type="submit" class="relative text-white font-semibold block mx-auto px-20 py-2 rounded -top-16" style="background: #0ac2c8">Update</button>
        </div>
      </div>
    </form>
    </div>
    {{-- upload modal --}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
                <div class="row">
                    <div class="col-md-8">
                        <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                    </div>
                    <div class="col-md-4">
                        <div class="preview"></div>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="crop">Crop</button>
          </div>
        </div>
      </div>
    </div>
  </main>
<script>
  var $modal = $('#modal');
  var image = document.getElementById('image');
  var cropper;
  var filesValue;
  var width, height;
  var position;
  const headers ={
        'Accept': 'application/json'
      };
  var api_token = $("meta[name=api-token]").attr('content');

  function editImg(pos) {
    position = pos;
    const hidden = document.getElementById("hidden-input");
    $("input#position").val(position);

    hidden.click();
    hidden.onchange = (e) => {
        var files = e.target.files;
        var done = function (url) {
          console.log(url);
          image.src = url;
          $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];

          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
    };
  }

  $modal.on('shown.bs.modal', function () {
    var ratio;
    switch (position) {
      case 'top':
        ratio = 0.8;
        width = 1000;
        height = 1250;
        break;
      case 'round':
        ratio = 1;
        width = 160;
        height = 160
        break;
      case 'portfolio':
        ratio = 1.333333;
        width = 1000;
        height = 750;
        break;
      case 'partnership':
        ratio = 2;
        width = 1000;
        height = 500;
        break;
      default:
        break;
    }
    cropper = new Cropper(image, {
    aspectRatio: ratio,
    viewMode: 3,
    preview: '.preview'
    });
  }).on('hidden.bs.modal', function () {
    cropper.destroy();
    cropper = null;
  });
  $("#crop").click(function(){
    canvas = cropper.getCroppedCanvas({
      width: width,
      height: height,
    });

    $("#uploadModal").show();

    canvas.toBlob(function(blob) {
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob); 
      reader.onloadend = function() {
        var base64data = reader.result;
        switch (position) {
          case 'top':
            $("img#top-image").attr('src', base64data);
            var url = "{{ url('/') }}/api/saveImage?api_token=";
            url = url + api_token;
            $.ajax({
              url: url,
              type: "POST",
              headers: headers,
              data: {
                'image': base64data,
                'position': 'top',
              },
              success: function(res) {
                $("#uploadModal").hide();
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
              }
            });

            break;
          case 'round':
            $("img#round-image").attr('src', base64data);
            var url = "{{ url('/') }}/api/saveImage?api_token=";
            url = url + api_token;
            $.ajax({
              url: url,
              type: "POST",
              headers: headers,
              data: {
                'image': base64data,
                'position': 'round',
              },
              success: function(res) {
                console.log(res);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
              }
            });

            break;
          case 'portfolio':
            var divElement = $('<div id="gallery-item" class="float-left mr-3 my-2 relative"></div>');
            var imgElement = $('<img src="' + base64data + '" alt="" class="rounded-sm" style="width: 65px; box-shadow:0 0 5px #333">');
            var linkElement = $('<a class="block absolute w-5 h-5 text-center rounded-full bg-red-600 text-white text-xs -top-2 -right-2" onclick="removeImage($(this), \'portfolio\')"><span class="leading-5">X</span></a>');
            divElement.append(imgElement);
            divElement.append(linkElement);
            $("#portfolio-gallery").append(divElement);

            var url = "{{ url('/') }}/api/saveImage?api_token=";
            url = url + api_token;
            $.ajax({
              url: url,
              type: "POST",
              headers: headers,
              data: {
                'image': base64data,
                'position': 'portfolio',
              },
              success: function(res) {
                console.log(res.file);
                var filename = res.file.split('/');
                var filename = filename[filename.length - 1];
                var src = "{{ asset('img/profile-image') }}" + '/' + filename;
                $("#portfolio-gallery div:last-child img").attr("src", src);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
              }
            });

            
            break;
          case 'partnership':
            var divElement = $('<div id="gallery-item" class="float-left mr-3 my-2 relative"></div>');
            var imgElement = $('<img src="' + base64data + '" alt="" class="rounded-sm" style="width: 65px; box-shadow:0 0 5px #333">');
            var linkElement = $('<a class="block absolute w-5 h-5 text-center rounded-full bg-red-600 text-white text-xs -top-2 -right-2" onclick="removeImage($(this), \'partnership\')"><span class="leading-5">X</span></a>');
            divElement.append(imgElement);
            divElement.append(linkElement);
            $("#partnership-gallery").append(divElement);

            var url = "{{ url('/') }}/api/saveImage?api_token=";
            url = url + api_token;
            $.ajax({
              url: url,
              type: "POST",
              headers: headers,
              data: {
                'image': base64data,
                'position': 'partnership',
              },
              success: function(res) {
                console.log(res.file);
                var filename = res.file.split('/');
                var filename = filename[filename.length - 1];
                var src = "{{ asset('img/partnership-image') }}" + '/' + filename;
                $("#partnership-gallery div:last-child img").attr("src", src);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
              }
            });
            break;

          default:
            break;
        }
        $modal.modal('hide');
      }
      });
    });

    function removeImage(elem, item) {
      var src = elem.prev().attr('src').split('/');
      var file = src[src.length - 1].split('.');
      var filename = file[0];
      var url = "{{ url('/') }}/api/deleteImage?api_token=";
      url = url + api_token;
      $.ajax({
        url: url,
        type: "POST",
        headers: headers,
        data: {
          'filename': filename,
          'position': item,
        },
        success: function(res) {
          if(res.data == 'success') {
            elem.parent().remove();
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(XMLHttpRequest, textStatus, errorThrown);
        }
      });
    }
</script>
@endsection