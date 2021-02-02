<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .bg-img {
            background-image: url(//cdn.telstatic.xyz/background.jpg);
            background-size: cover;
        }

        /* ---- reset ---- */

        body,
        html {
            width: 100%;
            height: 100%;
            margin: 0;
            font: normal 100% Arial, Helvetica, sans-serif;
            background-color: black;
        }

        svg {
            position: absolute;
            top: 0;
            left: 0px;
            width: 40px;
            height: 600px;
        }

        .bg-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

    </style>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>
<body class="bg-img">

<div id="particles-js" class="flex-center position-ref full-height">
    @if (config('admin.auth.enable', true))
        <div class="top-right links" style="z-index: 99999;">
            @auth('admin')
                <a href="{{ url('/admin') }}">Home</a>
            @else
                <a href="{{ url('/admin/auth/login') }}">Login</a>
            @endauth
        </div>
    @endif

    <div class="content bg-bubbles">
        <div class="title m-b-md" style="color: white;margin-top: 20%">
            {{config('app.name')}}
        </div>

        <div class="links">
            <span style="font-size: large;color:white">合作</span>
            <span style="font-size: large;color:white">共赢</span>
            <span style="font-size: large;color:white">成长</span>
            <span style="font-size: large;color:white">信任</span>
        </div>
    </div>
</div>

</body>
<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 88,
                "density": {
                    "enable": true,
                    "value_area": 700
                }
            },
            "color": {
                "value": ["#aa73ff", "#f8c210", "#83d238", "#33b1f8"]
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 15
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1.5,
                    "opacity_min": 0.15,
                    "sync": false
                }
            },
            "size": {
                "value": 2.5,
                "random": false,
                "anim": {
                    "enable": true,
                    "speed": 2,
                    "size_min": 0.15,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 110,
                "color": "#33b1f8",
                "opacity": 0.25,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 1.6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "bounce",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });
</script>
</html>
