<script src="{{asset('assets/global/js/firebase/firebase-8.3.2.js')}}"></script>

<script>
    "use strict";

    var permission = null;
    var authenticated = '{{ auth()->user() ? true : false }}';
    var pushNotify = @json(gs('pn'));
    var firebaseConfig = @json(gs('firebase_config'));

    function pushNotifyAction(){
        permission = Notification.permission;

        if(!('Notification' in window)){
            notify('info', 'Push notifications not available in your browser. Try Chromium.')
        }
        else if(permission === 'denied' || permission == 'default'){ //Notice for users dashboard
            $('.notice').append(`
            <div class="card custom--card bg--light mb-4">
                <div class="p-3 d-flex align-items-center gap-3">
                    <span class="icon">
                        <i class="la text--lg la-bell"></i>
                    </span>

                    <div>
                        <h6 class="mb-1">@lang('Please Allow / Reset Browser Notification')</h6>
                        <p class="mb-0">If you want to get push notification then you have to allow notification from your browser</p>
                    </div>
                </div>
            </div>
            `);
        }
    }

    //If enable push notification from admin panel
    if(pushNotify == 1){
        pushNotifyAction();
    }

    //When users allow browser notification
    if(permission != 'denied' && firebaseConfig){

        //Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        navigator.serviceWorker.register("{{ asset('assets/global/js/firebase/firebase-messaging-sw.js') }}")

        .then((registration) => {
            messaging.useServiceWorker(registration);

            function initFirebaseMessagingRegistration() {
                messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function (token){
                    $.ajax({
                        url: '{{ route("user.add.device.token") }}',
                        type: 'POST',
                        data: {
                            token: token,
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(response){
                        },
                        error: function (err) {
                        },
                    });
                }).catch(function (error){
                });
            }

            messaging.onMessage(function (payload){
                const title = payload.notification.title;
                const options = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                    image: payload.notification.image,
                    click_action:payload.notification.click_action,
                    vibrate: [200, 100, 200]
                };
                new Notification(title, options);
            });

            //For authenticated users
            if(authenticated){
                initFirebaseMessagingRegistration();
            }

        });

    }
</script>
