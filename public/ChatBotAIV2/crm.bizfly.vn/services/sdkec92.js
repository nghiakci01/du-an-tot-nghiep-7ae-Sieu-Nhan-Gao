var curDomain = document.domain;
var CrmBizflyWebConfig = {};
var isRunningService = {};
var CrmBizflyServiceSdk = {
    base_url: 'https://webpush.bizfly.vn' ,
    apiUrl : 'https://crm.bizfly.vn' + '/public-api/public/',
    config: {},
    getHostName(link){
        try {
            return  new URL(link).hostname
        }
        catch (e) {

        }
    },
    getParameterByName:function (name,search) {
        if(!search)
        {
            search = location.search;
        }
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)","i"),
            results = regex.exec(search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },
    getDomain(){
        let hostName = curDomain;
        let url_ref = CrmBizflyServiceSdk.getParameterByName('url_ref');
        if(url_ref){
             hostName = CrmBizflyServiceSdk.getHostName(url_ref);

        }
        return hostName;
    },
    list_lv2:['com','edu','gov','org','biz','net'],
    getDomainName: function(hostname) {
        let names = hostname.split('.');
        let start = 1;
        if(names[1] && CrmBizflyServiceSdk.list_lv2.indexOf(names[1])!==-1 || names.length<=2)
        {
            start = 0;
        }
        return names.slice(start, names.length).join('.');
    },
        buildQueryString (object)
    {
        if (Object.prototype.toString.call(object) !== "[object Object]") return ""

        var args = [];
        for (var key in object) {
            destructure(key, object[key])
        }

        return args.join("&");

        function destructure(key, value) {
            if (Array.isArray(value)) {
                for (var i = 0; i < value.length; i++) {
                    destructure(key + "[" + i + "]", value[i])
                }
            }
            else if (Object.prototype.toString.call(value) === "[object Object]") {
                for (var i in value) {
                    destructure(key + "[" + i + "]", value[i])
                }
            }
            else args.push(encodeURIComponent(key) + (value != null && value !== "" ? "=" + encodeURIComponent(value) : ""))
        }
    },
    createImageTracking(src) {
        let newImage = document.createElement("img");
        newImage.src = src;
        newImage.width = 0;
        newImage.height = 0;
        newImage.style = "display:none";
        newImage.onload = function () {
            document.body.removeChild(newImage)
        };
        document.body.appendChild(newImage);
    },
    hashCode :function(str) {
        var i = str.length
        var hash1 = 5381
        var hash2 = 52711
        var hash3 = 527112

        while (i--) {
            const char = str.charCodeAt(i)
            hash1 = (hash1 * 33) ^ char
            hash2 = (hash2 * 33) ^ char
            hash3 = (hash3 * 33) ^ char

        }

        return ''+(hash1 >>> 0) * 4096 + (hash2 >>> 0)+(hash3 >>> 0)
    },
    async loadScripts(scripts,v = true) {
        function get(src,vv) {

            return new Promise(function (resolve, reject) {
                var el = document.createElement("script");
                el.async = true;

                el.id = 'crm_'+CrmBizflyServiceSdk.hashCode(src.split('?')[0]);
                if (document.getElementById(el.id)) return;

                el.addEventListener("load", function () {
                    resolve(src);
                }, false);
                el.addEventListener("error", function () {
                    reject(src);
                }, false);
                src = src &&  src.indexOf('?')!==-1? src : src  + (vv?'?v='+parseInt(new Date().getTime()/60/60/1000):'');
                el.src = src;
                if(document.getElementsByTagName("body")[0])
                {
                    (document.getElementsByTagName("body")[0]).appendChild(el);

                }
                else{
                    if(document.getElementsByTagName("head")[0])
                    {
                        (document.getElementsByTagName("head")[0]).appendChild(el);

                    }
                }

            });

        }

        const myPromises = scripts.map(async function (script, index) {
            return await get(script,v);
        });

        return await Promise.all(myPromises);
    },
    wait_and_run :function(condition,callback,option) {

        let time_start = Date.now();
        let time_out = option && option.time_out? option.time_out:20;

        let checkExist =  setInterval(function() {
            let con = condition();
            if (con ||  Date.now() - time_start>time_out*1000) {
                clearInterval(checkExist);
                if(con)
                {
                    callback(option)
                }
            }
        }, 200);
    },
    async postDataJson(url = '', data = {}) {
        const response = await fetch(url + '?' + CrmBizflyServiceSdk.buildQueryString(data),{cache: "force-cache"});
        return await response.json();
    },
    tracking(content) {
        let datatracking = {
            content: content,
            client: {
                href: window.location.href,
                host: window.location.host,
                hostname: window.location.hostname,
                protocol: window.location.protocol,
                port: window.location.port,
            }
        };
        //link tracking
        this.createImageTracking(atob("aHR0cHM6Ly9jcm0uYml6Zmx5LnZuL190cmFjay1lcnJvci1jbGllbnQ/bXNnPQ==") + btoa(JSON.stringify(datatracking)));
    },
    async loadServices(config) {
        if(CrmBizflyServiceSdk.isExclude()){
            return false;
        }
        try{
            let config_server  =  localStorage && localStorage.getItem('sdk_config_server') ? JSON.parse(localStorage.getItem('sdk_config_server')) : '';
            // lấy config từ server lấy service enable
            let link_get_config = 'https://webpush.bizfly.vn/public-api/public/' + 'services-enable'; //todo @toàn bổ sung cái này lấy config theo dự án

            CrmBizflyServiceSdkInit.url = curDomain;
            CrmBizflyServiceSdkInit.v = parseInt(new Date().getTime()/2/60/60/1000)
            if (config_server && config_server.timestamp >= parseInt(new Date().getTime()/60/60/1000) && config_server.version >= 14) {
                CrmBizflyWebConfig = config_server;
            } else {
                let configInServer = await this.postDataJson(link_get_config, CrmBizflyServiceSdkInit);
                if (typeof configInServer.data === "undefined") {
                    // console.log('configInServer.data is undefined');
                    if (!(localStorage && localStorage.getItem('configInServer_data_is_undefined'))) {
                        this.tracking({
                            type: 'error',
                            msg: 'configInServer.data is undefined',
                            data: configInServer,
                        });
                        localStorage.setItem('configInServer_data_is_undefined', 'ok')
                    }
                    return false;
                } else {
                    CrmBizflyWebConfig = configInServer.data;
                }
            }
            ///
            //todo: Load những file js cần thiết theo từng yêu cầu

            if (typeof CrmBizflyWebConfig.services !== 'undefined' && typeof CrmBizflyWebConfig.project_id !== 'undefined') {
                let services = CrmBizflyWebConfig.services;
                let domainActive = [ 
                    'crm.bizfly.vn', 
                    'dev.bizdev.vn', 
                    'crm.bizdev.vn', 
                    'dev.onprem.bizdev.vn', 
                    'crm.onprem.bizdev.vn',
                    'crm.doji.vn',
                ];
                let v = 1;
                if (typeof CrmBizflyWebConfig.version !== 'undefined') {
                    v = CrmBizflyWebConfig.version;
                }

                localStorage.removeItem('sdk_config_server');
                CrmBizflyWebConfig.timestamp =  parseInt(new Date().getTime()/60/60/1000);
                localStorage.setItem('sdk_config_server', JSON.stringify(CrmBizflyWebConfig));


                if ((typeof services.notification_marketing !== 'undefined' && services.notification_marketing === true)
                    || domainActive.indexOf(curDomain) >= 0 ) {
                    window['CrmBizflyAnalyticsObject'] = 'CrmBizflyTracking'; window['CrmBizflyTracking'] = window['CrmBizflyTracking'] || function () {
                        (window['CrmBizflyTracking'].q = window['CrmBizflyTracking'].q || []).push(arguments);
                    };
                    isRunningService.service = 'run notification';
                    CrmBizflyTracking('setWebId', CrmBizflyWebConfig.project_id);
                    if (typeof CrmBizflyServiceSdkInit.ns_3rd_id !== 'undefined') {
                        CrmBizflyTracking('ns_3rd_id', CrmBizflyServiceSdkInit.ns_3rd_id);
                    }
                    if (typeof CrmBizflyServiceSdkInit.fncCallBack !== 'undefined') {
                        CrmBizflyTracking('fncCallBack', CrmBizflyServiceSdkInit.fncCallBack);
                    }
                    this.loadScripts([CrmBizflyServiceSdk.base_url + '/services/notification-service.js?v=' + v]).then( ()  => {});
                }



            } else {
                return false;
            }
        }
        catch (e) {

        }


    },
    loadAdmTag(tag){
        if(tag){
            this.loadScripts(['https://deqik.com/tag/corejs/'+tag+'.js'],false).then( ()  => {
                // console.log(getinfoAdm())
                CRM_TRACKING_CLIENT.getCookiesAdm();
            });
        }

    },
    isExclude(){
        return window.location.hostname == 'cdnstoremedia.com';
    },
    init() {
        curDomain = CrmBizflyServiceSdk.getDomain();

        //Yêu cầu phải có biến này
        if (typeof CrmBizflyServiceSdkInit === "undefined") {
            return false;
        }

        if (typeof CrmBizflyServiceSdkInit.project_token === "undefined") {
            return false;
        }

        if(CrmBizflyServiceSdkInit && ['dev', 'local'].includes(CrmBizflyServiceSdkInit.env)){
            CrmBizflyServiceSdk.base_url = window.location.origin;
            CrmBizflyServiceSdk.apiUrl =  CrmBizflyServiceSdk.base_url + '/public-api/public/';
        }
        
        //loadservice
            window['TRACKING_BIZFLY_CRM'] = {
                project_token : CrmBizflyServiceSdkInit.project_token,
                base_url : CrmBizflyServiceSdk.base_url,
                web_tracking : CrmBizflyServiceSdk.base_url + '/web_tracking/tracking?',
                form_id : ['.contactForm'],
            };

            this.loadScripts([CrmBizflyServiceSdk.base_url + '/services/client.js?v=' + parseInt(new Date().getTime()/60/60/1000)]).then( ()  => {
                CRM_TRACKING_CLIENT.init();
                if(CrmBizflyServiceSdk.isExclude()){
                    return false;
                }

                let tag = '';
                if(CrmBizflyServiceSdk.getDomainName(curDomain)=='bizdev.vn'){
                    tag = 'ATM51SV52WY71'
                }
                else if(CrmBizflyServiceSdk.getDomainName(curDomain) =='bizfly.vn'){
                    tag = 'ATM5WIYXJ3WXJ'
                }
                else{
                     fetch('https://webpush.bizfly.vn/public-api/public/' + 'tag-domain?' + CrmBizflyServiceSdk.buildQueryString({domain:curDomain}),{cache: "force-cache"} )
                        .then(response => response.json())
                        .then(data => {
                            let tag = data && data.data && data.data.tag;
                            this.loadAdmTag(tag);
                        });

                }
                this.loadAdmTag(tag);

            });


        if(window.top === window.self){
            this.loadServices(CrmBizflyServiceSdkInit);
        }


    }
}

CrmBizflyServiceSdk.init();

