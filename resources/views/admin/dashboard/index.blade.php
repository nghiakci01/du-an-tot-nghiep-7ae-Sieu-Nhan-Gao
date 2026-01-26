@extends('layouts.admin') {{-- Đảm bảo bạn đã có layout admin --}}
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card welcome-banner bg-blue-800">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="p-4">
                                <h2 class="text-white">Explore Redesigned Able Pro</h2>
                                <p class="text-white">
                                    The Brand new User Interface with power of Bootstrap
                                    Components. Explore the Endless possibilities with Able
                                    Pro.
                                </p>
                                <a href="https://codedthemes.com/item/able-pro-dashboard-templates"
                                    class="btn btn-outline-light btn-buy">Download</a>
                            </div>
                        </div>
                        <div class="col-sm-6 text-center">
                            <div class="img-welcome-banner">
                                <img src="../assets/images/widget/welcome-banner.png" alt="img" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s bg-light-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M13 9H7" stroke="#4680FF" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M22.0002 10.9702V13.0302C22.0002 13.5802 21.5602 14.0302 21.0002 14.0502H19.0402C17.9602 14.0502 16.9702 13.2602 16.8802 12.1802C16.8202 11.5502 17.0602 10.9602 17.4802 10.5502C17.8502 10.1702 18.3602 9.9502 18.9202 9.9502H21.0002C21.5602 9.9702 22.0002 10.4202 22.0002 10.9702Z"
                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M17.48 10.55C17.06 10.96 16.82 11.55 16.88 12.18C16.97 13.26 17.96 14.05 19.04 14.05H21V15.5C21 18.5 19 20.5 16 20.5H7C4 20.5 2 18.5 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.50999 16.75 3.54999C19.33 3.84999 21 5.76 21 8.5V9.95001H18.92C18.36 9.95001 17.85 10.17 17.48 10.55Z"
                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">All Earnings</h6>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti ti-dots-vertical f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-body p-3 mt-3 rounded">
                        <div class="mt-3 row align-items-center">
                            <div class="col-7">
                                <div id="all-earnings-graph" style="min-height: 50px;">
                                    <div id="apexcharts1u9yxvuqg"
                                        class="apexcharts-canvas apexcharts1u9yxvuqg apexcharts-theme-light"
                                        style="width: 201px; height: 50px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                            xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="201"
                                            height="50">
                                            <foreignObject x="0" y="0" width="201" height="50">
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0"
                                                opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                                fill="#fefefe"></rect>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                                            <g class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(14.618181818181819, 0)">
                                                <defs>
                                                    <linearGradient x1="0" y1="0" x2="0"
                                                        y2="1" id="SvgjsLinearGradient1000">
                                                        <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                                            offset="0"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                    </linearGradient>
                                                    <clipPath id="gridRectMask1u9yxvuqg">
                                                        <rect width="175.76363636363635" height="54" x="-2" y="-2"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMask1u9yxvuqg">
                                                        <rect width="205" height="54" x="-16.618181818181817" y="-2"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMask1u9yxvuqg">
                                                        <rect width="171.76363636363635" height="50" x="0" y="0"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask1u9yxvuqg"></clipPath>
                                                    <clipPath id="nonForecastMask1u9yxvuqg"></clipPath>
                                                </defs>
                                                <line x1="0" y1="0" x2="0" y2="50"
                                                    stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                                    class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                    height="50" fill="url(#SvgjsLinearGradient1000)" filter="none"
                                                    fill-opacity="0.9" stroke-width="0"></line>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                        <line x1="-14.618181818181819" y1="0"
                                                            x2="186.38181818181818" y2="0" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="25"
                                                            x2="186.38181818181818" y2="25" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="50"
                                                            x2="186.38181818181818" y2="50" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                    <line x1="0" y1="50" x2="171.76363636363635"
                                                        y2="50" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                    <line x1="0" y1="1" x2="0" y2="50"
                                                        stroke="transparent" stroke-dasharray="0" stroke-linecap="butt">
                                                    </line>
                                                </g>
                                                <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                <g class="apexcharts-bar-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" rel="1" seriesName="series-1"
                                                        data:realIndex="0">
                                                        <path
                                                            d="M-6.245950413223139 48.001L-6.245950413223139 47.001C-6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001L4.245950413223139 45.001C5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001L6.245950413223139 48.001C6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001L-4.245950413223139 50.001C-5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001C-6.245950413223139 48.001 -6.245950413223139 48.001 -6.245950413223139 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M -6.245950413223139 48.001 L -6.245950413223139 47.001 C -6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001 L 4.245950413223139 45.001 C 5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001 L 6.245950413223139 48.001 C 6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001 L -4.245950413223139 50.001 C -5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001 Z "
                                                            pathFrom="M -6.245950413223139 50.001 L -6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L -6.245950413223139 50.001 Z"
                                                            cy="45" cx="6.245950413223139" j="0" val="10"
                                                            barHeight="5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M9.368925619834709 48.001L9.368925619834709 37.001C9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001L19.86082644628099 35.001C20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001L21.86082644628099 48.001C21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001L11.368925619834709 50.001C10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001C9.368925619834709 48.001 9.368925619834709 48.001 9.368925619834709 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 9.368925619834709 48.001 L 9.368925619834709 37.001 C 9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001 L 19.86082644628099 35.001 C 20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001 L 21.86082644628099 48.001 C 21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001 L 11.368925619834709 50.001 C 10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001 Z "
                                                            pathFrom="M 9.368925619834709 50.001 L 9.368925619834709 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 9.368925619834709 50.001 Z"
                                                            cy="35" cx="21.86082644628099" j="1" val="30"
                                                            barHeight="15" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M24.98380165289256 48.001L24.98380165289256 32.001000000000005C24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001L35.475702479338835 30.001C36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005L37.475702479338835 48.001C37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001L26.98380165289256 50.001C25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001C24.98380165289256 48.001 24.98380165289256 48.001 24.98380165289256 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 24.98380165289256 48.001 L 24.98380165289256 32.001000000000005 C 24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001 L 35.475702479338835 30.001 C 36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005 L 37.475702479338835 48.001 C 37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001 L 26.98380165289256 50.001 C 25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001 Z "
                                                            pathFrom="M 24.98380165289256 50.001 L 24.98380165289256 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 24.98380165289256 50.001 Z"
                                                            cy="30" cx="37.475702479338835" j="2" val="40"
                                                            barHeight="20" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M40.598677685950406 48.001L40.598677685950406 42.001C40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001L51.09057851239668 40.001C52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001L53.09057851239668 48.001C53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001L42.598677685950406 50.001C41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001C40.598677685950406 48.001 40.598677685950406 48.001 40.598677685950406 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 40.598677685950406 48.001 L 40.598677685950406 42.001 C 40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001 L 51.09057851239668 40.001 C 52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001 L 53.09057851239668 48.001 C 53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001 L 42.598677685950406 50.001 C 41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001 Z "
                                                            pathFrom="M 40.598677685950406 50.001 L 40.598677685950406 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 40.598677685950406 50.001 Z"
                                                            cy="40" cx="53.09057851239668" j="3" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M56.21355371900825 48.001L56.21355371900825 22.001C56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001L66.70545454545453 20.001C67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001L68.70545454545453 48.001C68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001L58.21355371900825 50.001C57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001C56.21355371900825 48.001 56.21355371900825 48.001 56.21355371900825 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 56.21355371900825 48.001 L 56.21355371900825 22.001 C 56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001 L 66.70545454545453 20.001 C 67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001 L 68.70545454545453 48.001 C 68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001 L 58.21355371900825 50.001 C 57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001 Z "
                                                            pathFrom="M 56.21355371900825 50.001 L 56.21355371900825 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 56.21355371900825 50.001 Z"
                                                            cy="20" cx="68.70545454545453" j="4" val="60"
                                                            barHeight="30" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M71.8284297520661 48.001L71.8284297520661 27.001C71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001L82.32033057851238 25.001C83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001L84.32033057851238 48.001C84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001L73.8284297520661 50.001C72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001C71.8284297520661 48.001 71.8284297520661 48.001 71.8284297520661 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 71.8284297520661 48.001 L 71.8284297520661 27.001 C 71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001 L 82.32033057851238 25.001 C 83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001 L 84.32033057851238 48.001 C 84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001 L 73.8284297520661 50.001 C 72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001 Z "
                                                            pathFrom="M 71.8284297520661 50.001 L 71.8284297520661 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 71.8284297520661 50.001 Z"
                                                            cy="25" cx="84.32033057851238" j="5" val="50"
                                                            barHeight="25" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M87.44330578512395 48.001L87.44330578512395 42.001C87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001L97.93520661157024 40.001C98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001L99.93520661157024 48.001C99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001L89.44330578512395 50.001C88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001C87.44330578512395 48.001 87.44330578512395 48.001 87.44330578512395 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 87.44330578512395 48.001 L 87.44330578512395 42.001 C 87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001 L 97.93520661157024 40.001 C 98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001 L 99.93520661157024 48.001 C 99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001 L 89.44330578512395 50.001 C 88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001 Z "
                                                            pathFrom="M 87.44330578512395 50.001 L 87.44330578512395 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 87.44330578512395 50.001 Z"
                                                            cy="40" cx="99.93520661157024" j="6" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M103.05818181818181 48.001L103.05818181818181 44.501C103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501L113.55008264462809 42.501C114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501L115.55008264462809 48.001C115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001L105.05818181818181 50.001C104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001C103.05818181818181 48.001 103.05818181818181 48.001 103.05818181818181 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 103.05818181818181 48.001 L 103.05818181818181 44.501 C 103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501 L 113.55008264462809 42.501 C 114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501 L 115.55008264462809 48.001 C 115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001 L 105.05818181818181 50.001 C 104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001 Z "
                                                            pathFrom="M 103.05818181818181 50.001 L 103.05818181818181 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 103.05818181818181 50.001 Z"
                                                            cy="42.5" cx="115.55008264462809" j="7" val="15"
                                                            barHeight="7.5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M118.67305785123965 48.001L118.67305785123965 42.001C118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001L129.16495867768592 40.001C130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001L131.16495867768592 48.001C131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001L120.67305785123965 50.001C119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001C118.67305785123965 48.001 118.67305785123965 48.001 118.67305785123965 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 118.67305785123965 48.001 L 118.67305785123965 42.001 C 118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001 L 129.16495867768592 40.001 C 130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001 L 131.16495867768592 48.001 C 131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001 L 120.67305785123965 50.001 C 119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001 Z "
                                                            pathFrom="M 118.67305785123965 50.001 L 118.67305785123965 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 118.67305785123965 50.001 Z"
                                                            cy="40" cx="131.16495867768592" j="8" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M134.2879338842975 48.001L134.2879338842975 39.501C134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501L144.77983471074378 37.501C145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501L146.77983471074378 48.001C146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001L136.2879338842975 50.001C135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001C134.2879338842975 48.001 134.2879338842975 48.001 134.2879338842975 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 134.2879338842975 48.001 L 134.2879338842975 39.501 C 134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501 L 144.77983471074378 37.501 C 145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501 L 146.77983471074378 48.001 C 146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001 L 136.2879338842975 50.001 C 135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001 Z "
                                                            pathFrom="M 134.2879338842975 50.001 L 134.2879338842975 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 134.2879338842975 50.001 Z"
                                                            cy="37.5" cx="146.77983471074378" j="9" val="25"
                                                            barHeight="12.5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M149.90280991735534 48.001L149.90280991735534 37.001C149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001L160.39471074380162 35.001C161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001L162.39471074380162 48.001C162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001L151.90280991735534 50.001C150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001C149.90280991735534 48.001 149.90280991735534 48.001 149.90280991735534 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 149.90280991735534 48.001 L 149.90280991735534 37.001 C 149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001 L 160.39471074380162 35.001 C 161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001 L 162.39471074380162 48.001 C 162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001 L 151.90280991735534 50.001 C 150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001 Z "
                                                            pathFrom="M 149.90280991735534 50.001 L 149.90280991735534 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 149.90280991735534 50.001 Z"
                                                            cy="35" cx="162.39471074380162" j="10" val="30"
                                                            barHeight="15" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M165.5176859504132 48.001L165.5176859504132 39.501C165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501L176.0095867768595 37.501C177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501L178.0095867768595 48.001C178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001L167.5176859504132 50.001C166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001C165.5176859504132 48.001 165.5176859504132 48.001 165.5176859504132 48.001 "
                                                            fill="rgba(70,128,255,0.85)" fill-opacity="1"
                                                            stroke="#4680ff" stroke-opacity="1" stroke-linecap="square"
                                                            stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask1u9yxvuqg)"
                                                            pathTo="M 165.5176859504132 48.001 L 165.5176859504132 39.501 C 165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501 L 176.0095867768595 37.501 C 177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501 L 178.0095867768595 48.001 C 178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001 L 167.5176859504132 50.001 C 166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001 Z "
                                                            pathFrom="M 165.5176859504132 50.001 L 165.5176859504132 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 165.5176859504132 50.001 Z"
                                                            cy="37.5" cx="178.0095867768595" j="11" val="25"
                                                            barHeight="12.5" barWidth="12.491900826446278"></path>
                                                        <g class="apexcharts-bar-goals-markers">
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask1u9yxvuqg)"></g>
                                                        </g>
                                                        <g class="apexcharts-bar-shadows apexcharts-hidden-element-shown">
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                                        data:realIndex="0"></g>
                                                </g>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-point-annotations apexcharts-hidden-element-shown">
                                                </g>
                                            </g>
                                        </svg>
                                        <div class="apexcharts-legend" style="max-height: 25px;"></div>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle"
                                                    style="color: rgb(70, 128, 255);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <h5 class="mb-1">$3,020</h5>
                                <p class="text-primary mb-0">
                                    <i class="ti ti-arrow-up-right"></i> 30.6%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s bg-light-warning">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21 7V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V7C3 4 4.5 2 8 2H16C19.5 2 21 4 21 7Z"
                                        stroke="#E58A00" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.6" d="M14.5 4.5V6.5C14.5 7.6 15.4 8.5 16.5 8.5H18.5"
                                        stroke="#E58A00" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.6" d="M8 13H12" stroke="#E58A00" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.6" d="M8 17H16" stroke="#E58A00" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Page Views</h6>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti ti-dots-vertical f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-body p-3 mt-3 rounded">
                        <div class="mt-3 row align-items-center">
                            <div class="col-7">
                                <div id="page-views-graph" style="min-height: 50px;">
                                    <div id="apexchartsa2ubfi0z"
                                        class="apexcharts-canvas apexchartsa2ubfi0z apexcharts-theme-light"
                                        style="width: 201px; height: 50px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            width="201" height="50">
                                            <foreignObject x="0" y="0" width="201" height="50">
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <rect width="0" height="0" x="0" y="0" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            </g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            </g>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                                            <g class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(14.618181818181819, 0)">
                                                <defs>
                                                    <linearGradient x1="0" y1="0" x2="0"
                                                        y2="1" id="SvgjsLinearGradient1001">
                                                        <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                                            offset="0"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                    </linearGradient>
                                                    <clipPath id="gridRectMaska2ubfi0z">
                                                        <rect width="175.76363636363635" height="54" x="-2" y="-2"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMaska2ubfi0z">
                                                        <rect width="205" height="54" x="-16.618181818181817" y="-2"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMaska2ubfi0z">
                                                        <rect width="171.76363636363635" height="50" x="0" y="0"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMaska2ubfi0z"></clipPath>
                                                    <clipPath id="nonForecastMaska2ubfi0z"></clipPath>
                                                </defs>
                                                <line x1="0" y1="0" x2="0" y2="50"
                                                    stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                                    class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                    height="50" fill="url(#SvgjsLinearGradient1001)" filter="none"
                                                    fill-opacity="0.9" stroke-width="0"></line>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                        <line x1="-14.618181818181819" y1="0"
                                                            x2="186.38181818181818" y2="0" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="25"
                                                            x2="186.38181818181818" y2="25" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="50"
                                                            x2="186.38181818181818" y2="50" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                    <line x1="0" y1="50" x2="171.76363636363635"
                                                        y2="50" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                    <line x1="0" y1="1" x2="0" y2="50"
                                                        stroke="transparent" stroke-dasharray="0" stroke-linecap="butt">
                                                    </line>
                                                </g>
                                                <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                <g class="apexcharts-bar-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" rel="1" seriesName="series-1"
                                                        data:realIndex="0">
                                                        <path
                                                            d="M-6.245950413223139 48.001L-6.245950413223139 47.001C-6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001L4.245950413223139 45.001C5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001L6.245950413223139 48.001C6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001L-4.245950413223139 50.001C-5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001C-6.245950413223139 48.001 -6.245950413223139 48.001 -6.245950413223139 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M -6.245950413223139 48.001 L -6.245950413223139 47.001 C -6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001 L 4.245950413223139 45.001 C 5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001 L 6.245950413223139 48.001 C 6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001 L -4.245950413223139 50.001 C -5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001 Z "
                                                            pathFrom="M -6.245950413223139 50.001 L -6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L -6.245950413223139 50.001 Z"
                                                            cy="45" cx="6.245950413223139" j="0" val="10"
                                                            barHeight="5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M9.368925619834709 48.001L9.368925619834709 37.001C9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001L19.86082644628099 35.001C20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001L21.86082644628099 48.001C21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001L11.368925619834709 50.001C10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001C9.368925619834709 48.001 9.368925619834709 48.001 9.368925619834709 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 9.368925619834709 48.001 L 9.368925619834709 37.001 C 9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001 L 19.86082644628099 35.001 C 20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001 L 21.86082644628099 48.001 C 21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001 L 11.368925619834709 50.001 C 10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001 Z "
                                                            pathFrom="M 9.368925619834709 50.001 L 9.368925619834709 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 9.368925619834709 50.001 Z"
                                                            cy="35" cx="21.86082644628099" j="1" val="30"
                                                            barHeight="15" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M24.98380165289256 48.001L24.98380165289256 32.001000000000005C24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001L35.475702479338835 30.001C36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005L37.475702479338835 48.001C37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001L26.98380165289256 50.001C25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001C24.98380165289256 48.001 24.98380165289256 48.001 24.98380165289256 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 24.98380165289256 48.001 L 24.98380165289256 32.001000000000005 C 24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001 L 35.475702479338835 30.001 C 36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005 L 37.475702479338835 48.001 C 37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001 L 26.98380165289256 50.001 C 25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001 Z "
                                                            pathFrom="M 24.98380165289256 50.001 L 24.98380165289256 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 24.98380165289256 50.001 Z"
                                                            cy="30" cx="37.475702479338835" j="2" val="40"
                                                            barHeight="20" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M40.598677685950406 48.001L40.598677685950406 42.001C40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001L51.09057851239668 40.001C52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001L53.09057851239668 48.001C53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001L42.598677685950406 50.001C41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001C40.598677685950406 48.001 40.598677685950406 48.001 40.598677685950406 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 40.598677685950406 48.001 L 40.598677685950406 42.001 C 40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001 L 51.09057851239668 40.001 C 52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001 L 53.09057851239668 48.001 C 53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001 L 42.598677685950406 50.001 C 41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001 Z "
                                                            pathFrom="M 40.598677685950406 50.001 L 40.598677685950406 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 40.598677685950406 50.001 Z"
                                                            cy="40" cx="53.09057851239668" j="3" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M56.21355371900825 48.001L56.21355371900825 22.001C56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001L66.70545454545453 20.001C67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001L68.70545454545453 48.001C68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001L58.21355371900825 50.001C57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001C56.21355371900825 48.001 56.21355371900825 48.001 56.21355371900825 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 56.21355371900825 48.001 L 56.21355371900825 22.001 C 56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001 L 66.70545454545453 20.001 C 67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001 L 68.70545454545453 48.001 C 68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001 L 58.21355371900825 50.001 C 57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001 Z "
                                                            pathFrom="M 56.21355371900825 50.001 L 56.21355371900825 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 56.21355371900825 50.001 Z"
                                                            cy="20" cx="68.70545454545453" j="4" val="60"
                                                            barHeight="30" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M71.8284297520661 48.001L71.8284297520661 27.001C71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001L82.32033057851238 25.001C83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001L84.32033057851238 48.001C84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001L73.8284297520661 50.001C72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001C71.8284297520661 48.001 71.8284297520661 48.001 71.8284297520661 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 71.8284297520661 48.001 L 71.8284297520661 27.001 C 71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001 L 82.32033057851238 25.001 C 83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001 L 84.32033057851238 48.001 C 84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001 L 73.8284297520661 50.001 C 72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001 Z "
                                                            pathFrom="M 71.8284297520661 50.001 L 71.8284297520661 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 71.8284297520661 50.001 Z"
                                                            cy="25" cx="84.32033057851238" j="5" val="50"
                                                            barHeight="25" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M87.44330578512395 48.001L87.44330578512395 42.001C87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001L97.93520661157024 40.001C98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001L99.93520661157024 48.001C99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001L89.44330578512395 50.001C88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001C87.44330578512395 48.001 87.44330578512395 48.001 87.44330578512395 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 87.44330578512395 48.001 L 87.44330578512395 42.001 C 87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001 L 97.93520661157024 40.001 C 98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001 L 99.93520661157024 48.001 C 99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001 L 89.44330578512395 50.001 C 88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001 Z "
                                                            pathFrom="M 87.44330578512395 50.001 L 87.44330578512395 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 87.44330578512395 50.001 Z"
                                                            cy="40" cx="99.93520661157024" j="6" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M103.05818181818181 48.001L103.05818181818181 44.501C103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501L113.55008264462809 42.501C114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501L115.55008264462809 48.001C115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001L105.05818181818181 50.001C104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001C103.05818181818181 48.001 103.05818181818181 48.001 103.05818181818181 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 103.05818181818181 48.001 L 103.05818181818181 44.501 C 103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501 L 113.55008264462809 42.501 C 114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501 L 115.55008264462809 48.001 C 115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001 L 105.05818181818181 50.001 C 104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001 Z "
                                                            pathFrom="M 103.05818181818181 50.001 L 103.05818181818181 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 103.05818181818181 50.001 Z"
                                                            cy="42.5" cx="115.55008264462809" j="7" val="15"
                                                            barHeight="7.5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M118.67305785123965 48.001L118.67305785123965 42.001C118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001L129.16495867768592 40.001C130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001L131.16495867768592 48.001C131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001L120.67305785123965 50.001C119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001C118.67305785123965 48.001 118.67305785123965 48.001 118.67305785123965 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 118.67305785123965 48.001 L 118.67305785123965 42.001 C 118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001 L 129.16495867768592 40.001 C 130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001 L 131.16495867768592 48.001 C 131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001 L 120.67305785123965 50.001 C 119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001 Z "
                                                            pathFrom="M 118.67305785123965 50.001 L 118.67305785123965 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 118.67305785123965 50.001 Z"
                                                            cy="40" cx="131.16495867768592" j="8" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M134.2879338842975 48.001L134.2879338842975 39.501C134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501L144.77983471074378 37.501C145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501L146.77983471074378 48.001C146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001L136.2879338842975 50.001C135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001C134.2879338842975 48.001 134.2879338842975 48.001 134.2879338842975 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 134.2879338842975 48.001 L 134.2879338842975 39.501 C 134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501 L 144.77983471074378 37.501 C 145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501 L 146.77983471074378 48.001 C 146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001 L 136.2879338842975 50.001 C 135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001 Z "
                                                            pathFrom="M 134.2879338842975 50.001 L 134.2879338842975 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 134.2879338842975 50.001 Z"
                                                            cy="37.5" cx="146.77983471074378" j="9" val="25"
                                                            barHeight="12.5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M149.90280991735534 48.001L149.90280991735534 37.001C149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001L160.39471074380162 35.001C161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001L162.39471074380162 48.001C162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001L151.90280991735534 50.001C150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001C149.90280991735534 48.001 149.90280991735534 48.001 149.90280991735534 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 149.90280991735534 48.001 L 149.90280991735534 37.001 C 149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001 L 160.39471074380162 35.001 C 161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001 L 162.39471074380162 48.001 C 162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001 L 151.90280991735534 50.001 C 150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001 Z "
                                                            pathFrom="M 149.90280991735534 50.001 L 149.90280991735534 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 149.90280991735534 50.001 Z"
                                                            cy="35" cx="162.39471074380162" j="10" val="30"
                                                            barHeight="15" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M165.5176859504132 48.001L165.5176859504132 39.501C165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501L176.0095867768595 37.501C177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501L178.0095867768595 48.001C178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001L167.5176859504132 50.001C166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001C165.5176859504132 48.001 165.5176859504132 48.001 165.5176859504132 48.001 "
                                                            fill="rgba(229,138,0,0.85)" fill-opacity="1" stroke="#e58a00"
                                                            stroke-opacity="1" stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaska2ubfi0z)"
                                                            pathTo="M 165.5176859504132 48.001 L 165.5176859504132 39.501 C 165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501 L 176.0095867768595 37.501 C 177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501 L 178.0095867768595 48.001 C 178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001 L 167.5176859504132 50.001 C 166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001 Z "
                                                            pathFrom="M 165.5176859504132 50.001 L 165.5176859504132 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 165.5176859504132 50.001 Z"
                                                            cy="37.5" cx="178.0095867768595" j="11" val="25"
                                                            barHeight="12.5" barWidth="12.491900826446278"></path>
                                                        <g class="apexcharts-bar-goals-markers">
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaska2ubfi0z)"></g>
                                                        </g>
                                                        <g class="apexcharts-bar-shadows apexcharts-hidden-element-shown">
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                                        data:realIndex="0"></g>
                                                </g>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-point-annotations apexcharts-hidden-element-shown">
                                                </g>
                                            </g>
                                        </svg>
                                        <div class="apexcharts-legend" style="max-height: 25px;"></div>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle"
                                                    style="color: rgb(229, 138, 0);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <h5 class="mb-1">290K+</h5>
                                <p class="text-warning mb-0">
                                    <i class="ti ti-arrow-up-right"></i> 30.6%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s bg-light-success">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 2V5" stroke="#2ca87f" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16 2V5" stroke="#2ca87f" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M3.5 9.08984H20.5" stroke="#2ca87f" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                        stroke="#2ca87f" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M15.6947 13.7002H15.7037" stroke="#2ca87f"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M15.6947 16.7002H15.7037" stroke="#2ca87f"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M11.9955 13.7002H12.0045" stroke="#2ca87f"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M11.9955 16.7002H12.0045" stroke="#2ca87f"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M8.29431 13.7002H8.30329" stroke="#2ca87f"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M8.29395 16.7002H8.30293" stroke="#2ca87f"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Total Task</h6>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti ti-dots-vertical f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-body p-3 mt-3 rounded">
                        <div class="mt-3 row align-items-center">
                            <div class="col-7">
                                <div id="total-task-graph" style="min-height: 50px;">
                                    <div id="apexcharts25ja8q5n"
                                        class="apexcharts-canvas apexcharts25ja8q5n apexcharts-theme-light"
                                        style="width: 201px; height: 50px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS"
                                            transform="translate(0, 0)" width="201" height="50">
                                            <foreignObject x="0" y="0" width="201" height="50">
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <rect width="0" height="0" x="0" y="0" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            </g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            </g>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)">
                                            </g>
                                            <g class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(14.618181818181819, 0)">
                                                <defs>
                                                    <linearGradient x1="0" y1="0" x2="0"
                                                        y2="1" id="SvgjsLinearGradient1002">
                                                        <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                                            offset="0"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                    </linearGradient>
                                                    <clipPath id="gridRectMask25ja8q5n">
                                                        <rect width="175.76363636363635" height="54" x="-2" y="-2"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMask25ja8q5n">
                                                        <rect width="205" height="54" x="-16.618181818181817"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMask25ja8q5n">
                                                        <rect width="171.76363636363635" height="50" x="0" y="0"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask25ja8q5n"></clipPath>
                                                    <clipPath id="nonForecastMask25ja8q5n"></clipPath>
                                                </defs>
                                                <line x1="0" y1="0" x2="0" y2="50"
                                                    stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                                    class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                    height="50" fill="url(#SvgjsLinearGradient1002)" filter="none"
                                                    fill-opacity="0.9" stroke-width="0"></line>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                        <line x1="-14.618181818181819" y1="0"
                                                            x2="186.38181818181818" y2="0" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="25"
                                                            x2="186.38181818181818" y2="25" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="50"
                                                            x2="186.38181818181818" y2="50" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                    <line x1="0" y1="50" x2="171.76363636363635"
                                                        y2="50" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                    <line x1="0" y1="1" x2="0"
                                                        y2="50" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                </g>
                                                <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                <g class="apexcharts-bar-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" rel="1" seriesName="series-1"
                                                        data:realIndex="0">
                                                        <path
                                                            d="M-6.245950413223139 48.001L-6.245950413223139 47.001C-6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001L4.245950413223139 45.001C5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001L6.245950413223139 48.001C6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001L-4.245950413223139 50.001C-5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001C-6.245950413223139 48.001 -6.245950413223139 48.001 -6.245950413223139 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M -6.245950413223139 48.001 L -6.245950413223139 47.001 C -6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001 L 4.245950413223139 45.001 C 5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001 L 6.245950413223139 48.001 C 6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001 L -4.245950413223139 50.001 C -5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001 Z "
                                                            pathFrom="M -6.245950413223139 50.001 L -6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L -6.245950413223139 50.001 Z"
                                                            cy="45" cx="6.245950413223139" j="0" val="10"
                                                            barHeight="5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M9.368925619834709 48.001L9.368925619834709 37.001C9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001L19.86082644628099 35.001C20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001L21.86082644628099 48.001C21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001L11.368925619834709 50.001C10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001C9.368925619834709 48.001 9.368925619834709 48.001 9.368925619834709 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 9.368925619834709 48.001 L 9.368925619834709 37.001 C 9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001 L 19.86082644628099 35.001 C 20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001 L 21.86082644628099 48.001 C 21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001 L 11.368925619834709 50.001 C 10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001 Z "
                                                            pathFrom="M 9.368925619834709 50.001 L 9.368925619834709 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 9.368925619834709 50.001 Z"
                                                            cy="35" cx="21.86082644628099" j="1" val="30"
                                                            barHeight="15" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M24.98380165289256 48.001L24.98380165289256 32.001000000000005C24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001L35.475702479338835 30.001C36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005L37.475702479338835 48.001C37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001L26.98380165289256 50.001C25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001C24.98380165289256 48.001 24.98380165289256 48.001 24.98380165289256 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 24.98380165289256 48.001 L 24.98380165289256 32.001000000000005 C 24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001 L 35.475702479338835 30.001 C 36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005 L 37.475702479338835 48.001 C 37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001 L 26.98380165289256 50.001 C 25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001 Z "
                                                            pathFrom="M 24.98380165289256 50.001 L 24.98380165289256 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 24.98380165289256 50.001 Z"
                                                            cy="30" cx="37.475702479338835" j="2"
                                                            val="40" barHeight="20"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M40.598677685950406 48.001L40.598677685950406 42.001C40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001L51.09057851239668 40.001C52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001L53.09057851239668 48.001C53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001L42.598677685950406 50.001C41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001C40.598677685950406 48.001 40.598677685950406 48.001 40.598677685950406 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 40.598677685950406 48.001 L 40.598677685950406 42.001 C 40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001 L 51.09057851239668 40.001 C 52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001 L 53.09057851239668 48.001 C 53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001 L 42.598677685950406 50.001 C 41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001 Z "
                                                            pathFrom="M 40.598677685950406 50.001 L 40.598677685950406 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 40.598677685950406 50.001 Z"
                                                            cy="40" cx="53.09057851239668" j="3" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M56.21355371900825 48.001L56.21355371900825 22.001C56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001L66.70545454545453 20.001C67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001L68.70545454545453 48.001C68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001L58.21355371900825 50.001C57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001C56.21355371900825 48.001 56.21355371900825 48.001 56.21355371900825 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 56.21355371900825 48.001 L 56.21355371900825 22.001 C 56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001 L 66.70545454545453 20.001 C 67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001 L 68.70545454545453 48.001 C 68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001 L 58.21355371900825 50.001 C 57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001 Z "
                                                            pathFrom="M 56.21355371900825 50.001 L 56.21355371900825 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 56.21355371900825 50.001 Z"
                                                            cy="20" cx="68.70545454545453" j="4" val="60"
                                                            barHeight="30" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M71.8284297520661 48.001L71.8284297520661 27.001C71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001L82.32033057851238 25.001C83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001L84.32033057851238 48.001C84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001L73.8284297520661 50.001C72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001C71.8284297520661 48.001 71.8284297520661 48.001 71.8284297520661 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 71.8284297520661 48.001 L 71.8284297520661 27.001 C 71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001 L 82.32033057851238 25.001 C 83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001 L 84.32033057851238 48.001 C 84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001 L 73.8284297520661 50.001 C 72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001 Z "
                                                            pathFrom="M 71.8284297520661 50.001 L 71.8284297520661 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 71.8284297520661 50.001 Z"
                                                            cy="25" cx="84.32033057851238" j="5" val="50"
                                                            barHeight="25" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M87.44330578512395 48.001L87.44330578512395 42.001C87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001L97.93520661157024 40.001C98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001L99.93520661157024 48.001C99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001L89.44330578512395 50.001C88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001C87.44330578512395 48.001 87.44330578512395 48.001 87.44330578512395 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 87.44330578512395 48.001 L 87.44330578512395 42.001 C 87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001 L 97.93520661157024 40.001 C 98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001 L 99.93520661157024 48.001 C 99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001 L 89.44330578512395 50.001 C 88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001 Z "
                                                            pathFrom="M 87.44330578512395 50.001 L 87.44330578512395 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 87.44330578512395 50.001 Z"
                                                            cy="40" cx="99.93520661157024" j="6" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M103.05818181818181 48.001L103.05818181818181 44.501C103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501L113.55008264462809 42.501C114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501L115.55008264462809 48.001C115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001L105.05818181818181 50.001C104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001C103.05818181818181 48.001 103.05818181818181 48.001 103.05818181818181 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 103.05818181818181 48.001 L 103.05818181818181 44.501 C 103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501 L 113.55008264462809 42.501 C 114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501 L 115.55008264462809 48.001 C 115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001 L 105.05818181818181 50.001 C 104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001 Z "
                                                            pathFrom="M 103.05818181818181 50.001 L 103.05818181818181 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 103.05818181818181 50.001 Z"
                                                            cy="42.5" cx="115.55008264462809" j="7"
                                                            val="15" barHeight="7.5"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M118.67305785123965 48.001L118.67305785123965 42.001C118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001L129.16495867768592 40.001C130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001L131.16495867768592 48.001C131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001L120.67305785123965 50.001C119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001C118.67305785123965 48.001 118.67305785123965 48.001 118.67305785123965 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 118.67305785123965 48.001 L 118.67305785123965 42.001 C 118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001 L 129.16495867768592 40.001 C 130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001 L 131.16495867768592 48.001 C 131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001 L 120.67305785123965 50.001 C 119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001 Z "
                                                            pathFrom="M 118.67305785123965 50.001 L 118.67305785123965 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 118.67305785123965 50.001 Z"
                                                            cy="40" cx="131.16495867768592" j="8"
                                                            val="20" barHeight="10"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M134.2879338842975 48.001L134.2879338842975 39.501C134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501L144.77983471074378 37.501C145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501L146.77983471074378 48.001C146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001L136.2879338842975 50.001C135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001C134.2879338842975 48.001 134.2879338842975 48.001 134.2879338842975 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 134.2879338842975 48.001 L 134.2879338842975 39.501 C 134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501 L 144.77983471074378 37.501 C 145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501 L 146.77983471074378 48.001 C 146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001 L 136.2879338842975 50.001 C 135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001 Z "
                                                            pathFrom="M 134.2879338842975 50.001 L 134.2879338842975 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 134.2879338842975 50.001 Z"
                                                            cy="37.5" cx="146.77983471074378" j="9"
                                                            val="25" barHeight="12.5"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M149.90280991735534 48.001L149.90280991735534 37.001C149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001L160.39471074380162 35.001C161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001L162.39471074380162 48.001C162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001L151.90280991735534 50.001C150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001C149.90280991735534 48.001 149.90280991735534 48.001 149.90280991735534 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 149.90280991735534 48.001 L 149.90280991735534 37.001 C 149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001 L 160.39471074380162 35.001 C 161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001 L 162.39471074380162 48.001 C 162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001 L 151.90280991735534 50.001 C 150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001 Z "
                                                            pathFrom="M 149.90280991735534 50.001 L 149.90280991735534 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 149.90280991735534 50.001 Z"
                                                            cy="35" cx="162.39471074380162" j="10"
                                                            val="30" barHeight="15"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M165.5176859504132 48.001L165.5176859504132 39.501C165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501L176.0095867768595 37.501C177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501L178.0095867768595 48.001C178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001L167.5176859504132 50.001C166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001C165.5176859504132 48.001 165.5176859504132 48.001 165.5176859504132 48.001 "
                                                            fill="rgba(44,168,127,0.85)" fill-opacity="1"
                                                            stroke="#2ca87f" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMask25ja8q5n)"
                                                            pathTo="M 165.5176859504132 48.001 L 165.5176859504132 39.501 C 165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501 L 176.0095867768595 37.501 C 177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501 L 178.0095867768595 48.001 C 178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001 L 167.5176859504132 50.001 C 166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001 Z "
                                                            pathFrom="M 165.5176859504132 50.001 L 165.5176859504132 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 165.5176859504132 50.001 Z"
                                                            cy="37.5" cx="178.0095867768595" j="11"
                                                            val="25" barHeight="12.5"
                                                            barWidth="12.491900826446278"></path>
                                                        <g class="apexcharts-bar-goals-markers">
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask25ja8q5n)"></g>
                                                        </g>
                                                        <g class="apexcharts-bar-shadows apexcharts-hidden-element-shown">
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                                        data:realIndex="0"></g>
                                                </g>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                                    </g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-point-annotations apexcharts-hidden-element-shown">
                                                </g>
                                            </g>
                                        </svg>
                                        <div class="apexcharts-legend" style="max-height: 25px;"></div>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                    shape="circle" style="color: rgb(44, 168, 127);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <h5 class="mb-1">839</h5>
                                <p class="text-success mb-0">
                                    <i class="ti ti-arrow-up-right"></i> New
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s bg-light-danger">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                        stroke="#DC2626" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path opacity="0.4" d="M8.4707 10.7402L12.0007 14.2602L15.5307 10.7402"
                                        stroke="#DC2626" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Download</h6>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti ti-dots-vertical f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-body p-3 mt-3 rounded">
                        <div class="mt-3 row align-items-center">
                            <div class="col-7">
                                <div id="download-graph" style="min-height: 50px;">
                                    <div id="apexchartsfruvt9g7"
                                        class="apexcharts-canvas apexchartsfruvt9g7 apexcharts-theme-light"
                                        style="width: 201px; height: 50px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS"
                                            transform="translate(0, 0)" width="201" height="50">
                                            <foreignObject x="0" y="0" width="201" height="50">
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <rect width="0" height="0" x="0" y="0" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            </g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            </g>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)">
                                            </g>
                                            <g class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(14.618181818181819, 0)">
                                                <defs>
                                                    <linearGradient x1="0" y1="0" x2="0"
                                                        y2="1" id="SvgjsLinearGradient1003">
                                                        <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                                            offset="0"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                    </linearGradient>
                                                    <clipPath id="gridRectMaskfruvt9g7">
                                                        <rect width="175.76363636363635" height="54" x="-2" y="-2"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMaskfruvt9g7">
                                                        <rect width="205" height="54" x="-16.618181818181817"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMaskfruvt9g7">
                                                        <rect width="171.76363636363635" height="50" x="0" y="0"
                                                            rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMaskfruvt9g7"></clipPath>
                                                    <clipPath id="nonForecastMaskfruvt9g7"></clipPath>
                                                </defs>
                                                <line x1="0" y1="0" x2="0" y2="50"
                                                    stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                                    class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                    height="50" fill="url(#SvgjsLinearGradient1003)" filter="none"
                                                    fill-opacity="0.9" stroke-width="0"></line>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                        <line x1="-14.618181818181819" y1="0"
                                                            x2="186.38181818181818" y2="0" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="25"
                                                            x2="186.38181818181818" y2="25" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="-14.618181818181819" y1="50"
                                                            x2="186.38181818181818" y2="50" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                    <line x1="0" y1="50" x2="171.76363636363635"
                                                        y2="50" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                    <line x1="0" y1="1" x2="0"
                                                        y2="50" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                </g>
                                                <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                <g class="apexcharts-bar-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" rel="1" seriesName="series-1"
                                                        data:realIndex="0">
                                                        <path
                                                            d="M-6.245950413223139 48.001L-6.245950413223139 47.001C-6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001L4.245950413223139 45.001C5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001L6.245950413223139 48.001C6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001L-4.245950413223139 50.001C-5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001C-6.245950413223139 48.001 -6.245950413223139 48.001 -6.245950413223139 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M -6.245950413223139 48.001 L -6.245950413223139 47.001 C -6.245950413223139 46.001 -5.245950413223139 45.001 -4.245950413223139 45.001 L 4.245950413223139 45.001 C 5.245950413223139 45.001 6.245950413223139 46.001 6.245950413223139 47.001 L 6.245950413223139 48.001 C 6.245950413223139 49.001 5.245950413223139 50.001 4.245950413223139 50.001 L -4.245950413223139 50.001 C -5.245950413223139 50.001 -6.245950413223139 49.001 -6.245950413223139 48.001 Z "
                                                            pathFrom="M -6.245950413223139 50.001 L -6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L 6.245950413223139 50.001 L -6.245950413223139 50.001 Z"
                                                            cy="45" cx="6.245950413223139" j="0" val="10"
                                                            barHeight="5" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M9.368925619834709 48.001L9.368925619834709 37.001C9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001L19.86082644628099 35.001C20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001L21.86082644628099 48.001C21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001L11.368925619834709 50.001C10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001C9.368925619834709 48.001 9.368925619834709 48.001 9.368925619834709 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 9.368925619834709 48.001 L 9.368925619834709 37.001 C 9.368925619834709 36.001 10.368925619834709 35.001 11.368925619834709 35.001 L 19.86082644628099 35.001 C 20.86082644628099 35.001 21.86082644628099 36.001 21.86082644628099 37.001 L 21.86082644628099 48.001 C 21.86082644628099 49.001 20.86082644628099 50.001 19.86082644628099 50.001 L 11.368925619834709 50.001 C 10.368925619834709 50.001 9.368925619834709 49.001 9.368925619834709 48.001 Z "
                                                            pathFrom="M 9.368925619834709 50.001 L 9.368925619834709 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 21.86082644628099 50.001 L 9.368925619834709 50.001 Z"
                                                            cy="35" cx="21.86082644628099" j="1" val="30"
                                                            barHeight="15" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M24.98380165289256 48.001L24.98380165289256 32.001000000000005C24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001L35.475702479338835 30.001C36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005L37.475702479338835 48.001C37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001L26.98380165289256 50.001C25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001C24.98380165289256 48.001 24.98380165289256 48.001 24.98380165289256 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 24.98380165289256 48.001 L 24.98380165289256 32.001000000000005 C 24.98380165289256 31.001000000000005 25.98380165289256 30.001 26.98380165289256 30.001 L 35.475702479338835 30.001 C 36.475702479338835 30.001 37.475702479338835 31.001000000000005 37.475702479338835 32.001000000000005 L 37.475702479338835 48.001 C 37.475702479338835 49.001 36.475702479338835 50.001 35.475702479338835 50.001 L 26.98380165289256 50.001 C 25.98380165289256 50.001 24.98380165289256 49.001 24.98380165289256 48.001 Z "
                                                            pathFrom="M 24.98380165289256 50.001 L 24.98380165289256 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 37.475702479338835 50.001 L 24.98380165289256 50.001 Z"
                                                            cy="30" cx="37.475702479338835" j="2"
                                                            val="40" barHeight="20"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M40.598677685950406 48.001L40.598677685950406 42.001C40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001L51.09057851239668 40.001C52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001L53.09057851239668 48.001C53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001L42.598677685950406 50.001C41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001C40.598677685950406 48.001 40.598677685950406 48.001 40.598677685950406 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 40.598677685950406 48.001 L 40.598677685950406 42.001 C 40.598677685950406 41.001 41.598677685950406 40.001 42.598677685950406 40.001 L 51.09057851239668 40.001 C 52.09057851239668 40.001 53.09057851239668 41.001 53.09057851239668 42.001 L 53.09057851239668 48.001 C 53.09057851239668 49.001 52.09057851239668 50.001 51.09057851239668 50.001 L 42.598677685950406 50.001 C 41.598677685950406 50.001 40.598677685950406 49.001 40.598677685950406 48.001 Z "
                                                            pathFrom="M 40.598677685950406 50.001 L 40.598677685950406 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 53.09057851239668 50.001 L 40.598677685950406 50.001 Z"
                                                            cy="40" cx="53.09057851239668" j="3" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M56.21355371900825 48.001L56.21355371900825 22.001C56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001L66.70545454545453 20.001C67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001L68.70545454545453 48.001C68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001L58.21355371900825 50.001C57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001C56.21355371900825 48.001 56.21355371900825 48.001 56.21355371900825 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 56.21355371900825 48.001 L 56.21355371900825 22.001 C 56.21355371900825 21.001 57.21355371900825 20.001 58.21355371900825 20.001 L 66.70545454545453 20.001 C 67.70545454545453 20.001 68.70545454545453 21.001 68.70545454545453 22.001 L 68.70545454545453 48.001 C 68.70545454545453 49.001 67.70545454545453 50.001 66.70545454545453 50.001 L 58.21355371900825 50.001 C 57.21355371900825 50.001 56.21355371900825 49.001 56.21355371900825 48.001 Z "
                                                            pathFrom="M 56.21355371900825 50.001 L 56.21355371900825 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 68.70545454545453 50.001 L 56.21355371900825 50.001 Z"
                                                            cy="20" cx="68.70545454545453" j="4" val="60"
                                                            barHeight="30" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M71.8284297520661 48.001L71.8284297520661 27.001C71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001L82.32033057851238 25.001C83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001L84.32033057851238 48.001C84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001L73.8284297520661 50.001C72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001C71.8284297520661 48.001 71.8284297520661 48.001 71.8284297520661 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 71.8284297520661 48.001 L 71.8284297520661 27.001 C 71.8284297520661 26.001 72.8284297520661 25.001 73.8284297520661 25.001 L 82.32033057851238 25.001 C 83.32033057851238 25.001 84.32033057851238 26.001 84.32033057851238 27.001 L 84.32033057851238 48.001 C 84.32033057851238 49.001 83.32033057851238 50.001 82.32033057851238 50.001 L 73.8284297520661 50.001 C 72.8284297520661 50.001 71.8284297520661 49.001 71.8284297520661 48.001 Z "
                                                            pathFrom="M 71.8284297520661 50.001 L 71.8284297520661 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 84.32033057851238 50.001 L 71.8284297520661 50.001 Z"
                                                            cy="25" cx="84.32033057851238" j="5" val="50"
                                                            barHeight="25" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M87.44330578512395 48.001L87.44330578512395 42.001C87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001L97.93520661157024 40.001C98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001L99.93520661157024 48.001C99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001L89.44330578512395 50.001C88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001C87.44330578512395 48.001 87.44330578512395 48.001 87.44330578512395 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 87.44330578512395 48.001 L 87.44330578512395 42.001 C 87.44330578512395 41.001 88.44330578512395 40.001 89.44330578512395 40.001 L 97.93520661157024 40.001 C 98.93520661157024 40.001 99.93520661157024 41.001 99.93520661157024 42.001 L 99.93520661157024 48.001 C 99.93520661157024 49.001 98.93520661157024 50.001 97.93520661157024 50.001 L 89.44330578512395 50.001 C 88.44330578512395 50.001 87.44330578512395 49.001 87.44330578512395 48.001 Z "
                                                            pathFrom="M 87.44330578512395 50.001 L 87.44330578512395 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 99.93520661157024 50.001 L 87.44330578512395 50.001 Z"
                                                            cy="40" cx="99.93520661157024" j="6" val="20"
                                                            barHeight="10" barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M103.05818181818181 48.001L103.05818181818181 44.501C103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501L113.55008264462809 42.501C114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501L115.55008264462809 48.001C115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001L105.05818181818181 50.001C104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001C103.05818181818181 48.001 103.05818181818181 48.001 103.05818181818181 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 103.05818181818181 48.001 L 103.05818181818181 44.501 C 103.05818181818181 43.501 104.05818181818181 42.501 105.05818181818181 42.501 L 113.55008264462809 42.501 C 114.55008264462809 42.501 115.55008264462809 43.501 115.55008264462809 44.501 L 115.55008264462809 48.001 C 115.55008264462809 49.001 114.55008264462809 50.001 113.55008264462809 50.001 L 105.05818181818181 50.001 C 104.05818181818181 50.001 103.05818181818181 49.001 103.05818181818181 48.001 Z "
                                                            pathFrom="M 103.05818181818181 50.001 L 103.05818181818181 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 115.55008264462809 50.001 L 103.05818181818181 50.001 Z"
                                                            cy="42.5" cx="115.55008264462809" j="7"
                                                            val="15" barHeight="7.5"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M118.67305785123965 48.001L118.67305785123965 42.001C118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001L129.16495867768592 40.001C130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001L131.16495867768592 48.001C131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001L120.67305785123965 50.001C119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001C118.67305785123965 48.001 118.67305785123965 48.001 118.67305785123965 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 118.67305785123965 48.001 L 118.67305785123965 42.001 C 118.67305785123965 41.001 119.67305785123965 40.001 120.67305785123965 40.001 L 129.16495867768592 40.001 C 130.16495867768592 40.001 131.16495867768592 41.001 131.16495867768592 42.001 L 131.16495867768592 48.001 C 131.16495867768592 49.001 130.16495867768592 50.001 129.16495867768592 50.001 L 120.67305785123965 50.001 C 119.67305785123965 50.001 118.67305785123965 49.001 118.67305785123965 48.001 Z "
                                                            pathFrom="M 118.67305785123965 50.001 L 118.67305785123965 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 131.16495867768592 50.001 L 118.67305785123965 50.001 Z"
                                                            cy="40" cx="131.16495867768592" j="8"
                                                            val="20" barHeight="10"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M134.2879338842975 48.001L134.2879338842975 39.501C134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501L144.77983471074378 37.501C145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501L146.77983471074378 48.001C146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001L136.2879338842975 50.001C135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001C134.2879338842975 48.001 134.2879338842975 48.001 134.2879338842975 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 134.2879338842975 48.001 L 134.2879338842975 39.501 C 134.2879338842975 38.501 135.2879338842975 37.501 136.2879338842975 37.501 L 144.77983471074378 37.501 C 145.77983471074378 37.501 146.77983471074378 38.501 146.77983471074378 39.501 L 146.77983471074378 48.001 C 146.77983471074378 49.001 145.77983471074378 50.001 144.77983471074378 50.001 L 136.2879338842975 50.001 C 135.2879338842975 50.001 134.2879338842975 49.001 134.2879338842975 48.001 Z "
                                                            pathFrom="M 134.2879338842975 50.001 L 134.2879338842975 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 146.77983471074378 50.001 L 134.2879338842975 50.001 Z"
                                                            cy="37.5" cx="146.77983471074378" j="9"
                                                            val="25" barHeight="12.5"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M149.90280991735534 48.001L149.90280991735534 37.001C149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001L160.39471074380162 35.001C161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001L162.39471074380162 48.001C162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001L151.90280991735534 50.001C150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001C149.90280991735534 48.001 149.90280991735534 48.001 149.90280991735534 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 149.90280991735534 48.001 L 149.90280991735534 37.001 C 149.90280991735534 36.001 150.90280991735534 35.001 151.90280991735534 35.001 L 160.39471074380162 35.001 C 161.39471074380162 35.001 162.39471074380162 36.001 162.39471074380162 37.001 L 162.39471074380162 48.001 C 162.39471074380162 49.001 161.39471074380162 50.001 160.39471074380162 50.001 L 151.90280991735534 50.001 C 150.90280991735534 50.001 149.90280991735534 49.001 149.90280991735534 48.001 Z "
                                                            pathFrom="M 149.90280991735534 50.001 L 149.90280991735534 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 162.39471074380162 50.001 L 149.90280991735534 50.001 Z"
                                                            cy="35" cx="162.39471074380162" j="10"
                                                            val="30" barHeight="15"
                                                            barWidth="12.491900826446278"></path>
                                                        <path
                                                            d="M165.5176859504132 48.001L165.5176859504132 39.501C165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501L176.0095867768595 37.501C177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501L178.0095867768595 48.001C178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001L167.5176859504132 50.001C166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001C165.5176859504132 48.001 165.5176859504132 48.001 165.5176859504132 48.001 "
                                                            fill="rgba(220,38,38,0.85)" fill-opacity="1"
                                                            stroke="#dc2626" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-bar-area undefined"
                                                            index="0" clip-path="url(#gridRectBarMaskfruvt9g7)"
                                                            pathTo="M 165.5176859504132 48.001 L 165.5176859504132 39.501 C 165.5176859504132 38.501 166.5176859504132 37.501 167.5176859504132 37.501 L 176.0095867768595 37.501 C 177.0095867768595 37.501 178.0095867768595 38.501 178.0095867768595 39.501 L 178.0095867768595 48.001 C 178.0095867768595 49.001 177.0095867768595 50.001 176.0095867768595 50.001 L 167.5176859504132 50.001 C 166.5176859504132 50.001 165.5176859504132 49.001 165.5176859504132 48.001 Z "
                                                            pathFrom="M 165.5176859504132 50.001 L 165.5176859504132 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 178.0095867768595 50.001 L 165.5176859504132 50.001 Z"
                                                            cy="37.5" cx="178.0095867768595" j="11"
                                                            val="25" barHeight="12.5"
                                                            barWidth="12.491900826446278"></path>
                                                        <g class="apexcharts-bar-goals-markers">
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMaskfruvt9g7)"></g>
                                                        </g>
                                                        <g class="apexcharts-bar-shadows apexcharts-hidden-element-shown">
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                                        data:realIndex="0"></g>
                                                </g>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line x1="-14.618181818181819" y1="0" x2="186.38181818181818"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                                    </g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown">
                                                </g>
                                                <g class="apexcharts-point-annotations apexcharts-hidden-element-shown">
                                                </g>
                                            </g>
                                        </svg>
                                        <div class="apexcharts-legend" style="max-height: 25px;"></div>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                    shape="circle" style="color: rgb(220, 38, 38);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <h5 class="mb-1">2,067</h5>
                                <p class="text-danger mb-0">
                                    <i class="ti ti-arrow-up-right"></i> 30.6%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1">
                            <h5 class="mb-0">Repeat customer rate</h5>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti ti-dots f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-end my-2">
                        5.44% <span class="badge bg-success">+2.6%</span>
                    </h5>
                    <div id="customer-rate-graph" style="min-height: 245px;">
                        <div id="apexchartsy2z2p2gy" class="apexcharts-canvas apexchartsy2z2p2gy apexcharts-theme-light"
                            style="width: 650px; height: 230px;"><svg xmlns="http://www.w3.org/2000/svg"
                                version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS"
                                transform="translate(0, 0)" width="650" height="230">
                                <foreignObject x="0" y="0" width="650" height="230">
                                    <style type="text/css">
                                        .apexcharts-flip-y {
                                            transform: scaleY(-1) translateY(-100%);
                                            transform-origin: top;
                                            transform-box: fill-box;
                                        }

                                        .apexcharts-flip-x {
                                            transform: scaleX(-1);
                                            transform-origin: center;
                                            transform-box: fill-box;
                                        }

                                        .apexcharts-legend {
                                            display: flex;
                                            overflow: auto;
                                            padding: 0 10px;
                                        }

                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                            flex-direction: column;
                                        }

                                        .apexcharts-legend-group {
                                            display: flex;
                                        }

                                        .apexcharts-legend-group-vertical {
                                            flex-direction: column-reverse;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom,
                                        .apexcharts-legend.apx-legend-position-top {
                                            flex-wrap: wrap
                                        }

                                        .apexcharts-legend.apx-legend-position-right,
                                        .apexcharts-legend.apx-legend-position-left {
                                            flex-direction: column;
                                            bottom: 0;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                        .apexcharts-legend.apx-legend-position-right,
                                        .apexcharts-legend.apx-legend-position-left {
                                            justify-content: flex-start;
                                            align-items: flex-start;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                            justify-content: center;
                                            align-items: center;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                            justify-content: flex-end;
                                            align-items: flex-end;
                                        }

                                        .apexcharts-legend-series {
                                            cursor: pointer;
                                            line-height: normal;
                                            display: flex;
                                            align-items: center;
                                        }

                                        .apexcharts-legend-text {
                                            position: relative;
                                            font-size: 14px;
                                        }

                                        .apexcharts-legend-text *,
                                        .apexcharts-legend-marker * {
                                            pointer-events: none;
                                        }

                                        .apexcharts-legend-marker {
                                            position: relative;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            cursor: pointer;
                                            margin-right: 1px;
                                        }

                                        .apexcharts-legend-series.apexcharts-no-click {
                                            cursor: auto;
                                        }

                                        .apexcharts-legend .apexcharts-hidden-zero-series,
                                        .apexcharts-legend .apexcharts-hidden-null-series {
                                            display: none !important;
                                        }

                                        .apexcharts-inactive-legend {
                                            opacity: 0.45;
                                        }
                                    </style>
                                </foreignObject>
                                <rect width="0" height="0" x="0" y="0" rx="0" ry="0"
                                    opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                    fill="#fefefe"></rect>
                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                <g class="apexcharts-yaxis" rel="0"
                                    transform="translate(9.516179084777832, 0)">
                                    <g class="apexcharts-yaxis-texts-g"><text x="20" y="33.666666666666664"
                                            text-anchor="end" dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>90</tspan>
                                            <title>90</title>
                                        </text><text x="20" y="61.04826635805765" text-anchor="end"
                                            dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>80</tspan>
                                            <title>80</title>
                                        </text><text x="20" y="88.42986604944863" text-anchor="end"
                                            dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>70</tspan>
                                            <title>70</title>
                                        </text><text x="20" y="115.81146574083962" text-anchor="end"
                                            dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>60</tspan>
                                            <title>60</title>
                                        </text><text x="20" y="143.1930654322306" text-anchor="end"
                                            dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>50</tspan>
                                            <title>50</title>
                                        </text><text x="20" y="170.5746651236216" text-anchor="end"
                                            dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>40</tspan>
                                            <title>40</title>
                                        </text><text x="20" y="197.9562648150126" text-anchor="end"
                                            dominant-baseline="auto" font-size="11px"
                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                            fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>30</tspan>
                                            <title>30</title>
                                        </text></g>
                                </g>
                                <g class="apexcharts-inner apexcharts-graphical"
                                    transform="translate(39.51617908477783, 30)">
                                    <defs>
                                        <clipPath id="gridRectMasky2z2p2gy">
                                            <rect width="593.6138982772827" height="169.28959814834593" x="-2.5"
                                                y="-2.5" rx="0" ry="0" opacity="1"
                                                stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                            </rect>
                                        </clipPath>
                                        <clipPath id="gridRectBarMasky2z2p2gy">
                                            <rect width="593.6138982772827" height="169.28959814834593" x="-2.5"
                                                y="-2.5" rx="0" ry="0" opacity="1"
                                                stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                            </rect>
                                        </clipPath>
                                        <clipPath id="gridRectMarkerMasky2z2p2gy">
                                            <rect width="588.6138982772827" height="164.28959814834593" x="0" y="0"
                                                rx="0" ry="0" opacity="1" stroke-width="0"
                                                stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="forecastMasky2z2p2gy"></clipPath>
                                        <clipPath id="nonForecastMasky2z2p2gy"></clipPath>
                                        <linearGradient x1="0" y1="0" x2="0" y2="1"
                                            id="SvgjsLinearGradient1004">
                                            <stop stop-opacity="0.5" stop-color="rgba(13,110,253,0.5)" offset="0">
                                            </stop>
                                            <stop stop-opacity="0" stop-color="rgba(255,255,255,0)" offset="1">
                                            </stop>
                                            <stop stop-opacity="0" stop-color="rgba(255,255,255,0)" offset="1">
                                            </stop>
                                        </linearGradient>
                                    </defs>
                                    <line x1="0" y1="0" x2="0" y2="164.28959814834593"
                                        stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                        class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                        height="164.28959814834593" fill="#b1b9c4" filter="none" fill-opacity="0.9"
                                        stroke-width="1"></line>
                                    <g class="apexcharts-grid">
                                        <g class="apexcharts-gridlines-horizontal">
                                            <line x1="0" y1="27.38159969139099" x2="588.6138982772827"
                                                y2="27.38159969139099" stroke="#e0e0e0" stroke-dasharray="4"
                                                stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            <line x1="0" y1="54.76319938278198" x2="588.6138982772827"
                                                y2="54.76319938278198" stroke="#e0e0e0" stroke-dasharray="4"
                                                stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            <line x1="0" y1="82.14479907417297" x2="588.6138982772827"
                                                y2="82.14479907417297" stroke="#e0e0e0" stroke-dasharray="4"
                                                stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            <line x1="0" y1="109.52639876556395" x2="588.6138982772827"
                                                y2="109.52639876556395" stroke="#e0e0e0" stroke-dasharray="4"
                                                stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            <line x1="0" y1="136.90799845695494" x2="588.6138982772827"
                                                y2="136.90799845695494" stroke="#e0e0e0" stroke-dasharray="4"
                                                stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        </g>
                                        <g class="apexcharts-gridlines-vertical"></g>
                                        <line x1="0" y1="164.28959814834593" x2="588.6138982772827"
                                            y2="164.28959814834593" stroke="transparent" stroke-dasharray="0"
                                            stroke-linecap="butt"></line>
                                        <line x1="0" y1="1" x2="0" y2="164.28959814834593"
                                            stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                    </g>
                                    <g class="apexcharts-grid-borders">
                                        <line x1="0" y1="0" x2="588.6138982772827" y2="0"
                                            stroke="#e0e0e0" stroke-dasharray="4" stroke-linecap="butt"
                                            class="apexcharts-gridline"></line>
                                        <line x1="0" y1="164.28959814834593" x2="588.6138982772827"
                                            y2="164.28959814834593" stroke="#e0e0e0" stroke-dasharray="4"
                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                    </g>
                                    <g class="apexcharts-area-series apexcharts-plot-series">
                                        <g class="apexcharts-series" zIndex="0" seriesName="series-1"
                                            data:longestSeries="true" rel="1" data:realIndex="0">
                                            <path
                                                d="M0 164.28959814834593C18.728624036095358 164.28959814834593 34.781730352748525 82.14479907417297 53.510354388843886 82.14479907417297C72.23897842493925 82.14479907417297 88.29208474159242 136.90799845695494 107.02070877768777 136.90799845695494C125.74933281378313 136.90799845695494 141.80243913043628 54.76319938278198 160.53106316653165 54.76319938278198C179.25968720262702 54.76319938278198 195.31279351928018 109.52639876556395 214.04141755537555 109.52639876556395C232.77004159147089 109.52639876556395 248.82314790812407 0 267.5517719442194 0C286.2803959803148 0 302.33350229696794 109.52639876556395 321.0621263330633 109.52639876556395C339.7907503691587 109.52639876556395 355.84385668581183 95.83559891986846 374.5724807219072 95.83559891986846C393.30110475800257 95.83559891986846 409.3542110746557 123.21719861125945 428.0828351107511 123.21719861125945C446.81145914684646 123.21719861125945 462.8645654634996 82.14479907417297 481.593189499595 82.14479907417297C500.32181353569035 82.14479907417297 516.3749198523435 109.52639876556395 535.1035438884388 109.52639876556395C553.8321679245341 109.52639876556395 569.8852742411874 68.45399922847747 588.6138982772827 68.45399922847747C588.6138982772827 68.45399922847747 588.6138982772827 68.45399922847747 588.6138982772827 164.28959814834593L0 164.28959814834593C0 164.28959814834593 0 164.28959814834593 0 164.28959814834593 "
                                                fill="url(#SvgjsLinearGradient1004)" fill-opacity="1" stroke="none"
                                                stroke-opacity="1" stroke-linecap="butt" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-area" index="0"
                                                clip-path="url(#gridRectMasky2z2p2gy)"
                                                pathTo="M 0 164.28959814834593C 18.728624036095358 164.28959814834593 34.781730352748525 82.14479907417297 53.510354388843886 82.14479907417297C 72.23897842493925 82.14479907417297 88.29208474159242 136.90799845695494 107.02070877768777 136.90799845695494C 125.74933281378313 136.90799845695494 141.80243913043628 54.76319938278198 160.53106316653165 54.76319938278198C 179.25968720262702 54.76319938278198 195.31279351928018 109.52639876556395 214.04141755537555 109.52639876556395C 232.77004159147089 109.52639876556395 248.82314790812407 0 267.5517719442194 0C 286.2803959803148 0 302.33350229696794 109.52639876556395 321.0621263330633 109.52639876556395C 339.7907503691587 109.52639876556395 355.84385668581183 95.83559891986846 374.5724807219072 95.83559891986846C 393.30110475800257 95.83559891986846 409.3542110746557 123.21719861125945 428.0828351107511 123.21719861125945C 446.81145914684646 123.21719861125945 462.8645654634996 82.14479907417297 481.593189499595 82.14479907417297C 500.32181353569035 82.14479907417297 516.3749198523435 109.52639876556395 535.1035438884388 109.52639876556395C 553.8321679245341 109.52639876556395 569.8852742411874 68.45399922847747 588.6138982772827 68.45399922847747C 588.6138982772827 68.45399922847747 588.6138982772827 68.45399922847747 588.6138982772827 164.28959814834593 L 0 164.28959814834593z"
                                                pathFrom="M 0 164.28959814834593 L 0 164.28959814834593 L 53.510354388843886 164.28959814834593 L 107.02070877768777 164.28959814834593 L 160.53106316653165 164.28959814834593 L 214.04141755537555 164.28959814834593 L 267.5517719442194 164.28959814834593 L 321.0621263330633 164.28959814834593 L 374.5724807219072 164.28959814834593 L 428.0828351107511 164.28959814834593 L 481.593189499595 164.28959814834593 L 535.1035438884388 164.28959814834593 L 588.6138982772827 164.28959814834593z">
                                            </path>
                                            <path
                                                d="M0 164.28959814834593C18.728624036095358 164.28959814834593 34.781730352748525 82.14479907417297 53.510354388843886 82.14479907417297C72.23897842493925 82.14479907417297 88.29208474159242 136.90799845695494 107.02070877768777 136.90799845695494C125.74933281378313 136.90799845695494 141.80243913043628 54.76319938278198 160.53106316653165 54.76319938278198C179.25968720262702 54.76319938278198 195.31279351928018 109.52639876556395 214.04141755537555 109.52639876556395C232.77004159147089 109.52639876556395 248.82314790812407 0 267.5517719442194 0C286.2803959803148 0 302.33350229696794 109.52639876556395 321.0621263330633 109.52639876556395C339.7907503691587 109.52639876556395 355.84385668581183 95.83559891986846 374.5724807219072 95.83559891986846C393.30110475800257 95.83559891986846 409.3542110746557 123.21719861125945 428.0828351107511 123.21719861125945C446.81145914684646 123.21719861125945 462.8645654634996 82.14479907417297 481.593189499595 82.14479907417297C500.32181353569035 82.14479907417297 516.3749198523435 109.52639876556395 535.1035438884388 109.52639876556395C553.8321679245341 109.52639876556395 569.8852742411874 68.45399922847747 588.6138982772827 68.45399922847747C588.6138982772827 68.45399922847747 588.6138982772827 68.45399922847747 588.6138982772827 68.45399922847747 "
                                                fill="none" fill-opacity="1" stroke="#0d6efd" stroke-opacity="1"
                                                stroke-linecap="butt" stroke-width="1" stroke-dasharray="0"
                                                class="apexcharts-area" index="0"
                                                clip-path="url(#gridRectMasky2z2p2gy)"
                                                pathTo="M 0 164.28959814834593C 18.728624036095358 164.28959814834593 34.781730352748525 82.14479907417297 53.510354388843886 82.14479907417297C 72.23897842493925 82.14479907417297 88.29208474159242 136.90799845695494 107.02070877768777 136.90799845695494C 125.74933281378313 136.90799845695494 141.80243913043628 54.76319938278198 160.53106316653165 54.76319938278198C 179.25968720262702 54.76319938278198 195.31279351928018 109.52639876556395 214.04141755537555 109.52639876556395C 232.77004159147089 109.52639876556395 248.82314790812407 0 267.5517719442194 0C 286.2803959803148 0 302.33350229696794 109.52639876556395 321.0621263330633 109.52639876556395C 339.7907503691587 109.52639876556395 355.84385668581183 95.83559891986846 374.5724807219072 95.83559891986846C 393.30110475800257 95.83559891986846 409.3542110746557 123.21719861125945 428.0828351107511 123.21719861125945C 446.81145914684646 123.21719861125945 462.8645654634996 82.14479907417297 481.593189499595 82.14479907417297C 500.32181353569035 82.14479907417297 516.3749198523435 109.52639876556395 535.1035438884388 109.52639876556395C 553.8321679245341 109.52639876556395 569.8852742411874 68.45399922847747 588.6138982772827 68.45399922847747"
                                                pathFrom="M 0 164.28959814834593 L 0 164.28959814834593 L 53.510354388843886 164.28959814834593 L 107.02070877768777 164.28959814834593 L 160.53106316653165 164.28959814834593 L 214.04141755537555 164.28959814834593 L 267.5517719442194 164.28959814834593 L 321.0621263330633 164.28959814834593 L 374.5724807219072 164.28959814834593 L 428.0828351107511 164.28959814834593 L 481.593189499595 164.28959814834593 L 535.1035438884388 164.28959814834593 L 588.6138982772827 164.28959814834593"
                                                fill-rule="evenodd"></path>
                                            <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                data:realIndex="0">
                                                <g class="apexcharts-series-markers">
                                                    <path d="M 0, 0
               m -0, 0
               a 0,0 0 1,0 0,0
               a 0,0 0 1,0 -0,0" fill="#0d6efd" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                        stroke-linecap="butt" stroke-width="2" stroke-dasharray="0"
                                                        cx="0" cy="0" shape="circle"
                                                        class="apexcharts-marker wtwhg1xiri no-pointer-events"
                                                        default-marker-size="0"></path>
                                                </g>
                                            </g>
                                        </g>
                                        <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                    </g>
                                    <line x1="0" y1="0" x2="588.6138982772827" y2="0"
                                        stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                        class="apexcharts-ycrosshairs"></line>
                                    <line x1="0" y1="0" x2="588.6138982772827" y2="0"
                                        stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                        class="apexcharts-ycrosshairs-hidden"></line>
                                    <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                        <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text x="0"
                                                y="192.28959814834593" text-anchor="middle" dominant-baseline="auto"
                                                font-size="12px" font-family="Helvetica, Arial, sans-serif"
                                                font-weight="400" fill="#373d3f"
                                                class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Jan</tspan>
                                                <title>Jan</title>
                                            </text><text x="53.51035438884388" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Feb</tspan>
                                                <title>Feb</title>
                                            </text><text x="107.02070877768776" y="192.28959814834593"
                                                text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Mar</tspan>
                                                <title>Mar</title>
                                            </text><text x="160.53106316653165" y="192.28959814834593"
                                                text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Apr</tspan>
                                                <title>Apr</title>
                                            </text><text x="214.04141755537555" y="192.28959814834593"
                                                text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>May</tspan>
                                                <title>May</title>
                                            </text><text x="267.5517719442194" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Jun</tspan>
                                                <title>Jun</title>
                                            </text><text x="321.0621263330633" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Jul</tspan>
                                                <title>Jul</title>
                                            </text><text x="374.5724807219072" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Aug</tspan>
                                                <title>Aug</title>
                                            </text><text x="428.0828351107511" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Sep</tspan>
                                                <title>Sep</title>
                                            </text><text x="481.593189499595" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Oct</tspan>
                                                <title>Oct</title>
                                            </text><text x="535.1035438884389" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Nov</tspan>
                                                <title>Nov</title>
                                            </text><text x="588.6138982772828" y="192.28959814834593" text-anchor="middle"
                                                dominant-baseline="auto" font-size="12px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Dec</tspan>
                                                <title>Dec</title>
                                            </text></g>
                                    </g>
                                    <g class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown"></g>
                                    <g class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown"></g>
                                    <g class="apexcharts-point-annotations apexcharts-hidden-element-shown"></g>
                                </g>
                                <rect width="0" height="0" x="0" y="0" rx="0" ry="0"
                                    opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                    fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                                <rect width="0" height="0" x="0" y="0" rx="0" ry="0"
                                    opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                    fill="#fefefe" class="apexcharts-selection-rect"></rect>
                            </svg>
                            <div class="apexcharts-legend" style="max-height: 115px;"></div>
                            <div class="apexcharts-tooltip apexcharts-theme-light">
                                <div class="apexcharts-tooltip-title"
                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                    style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle"
                                        style="color: rgb(13, 110, 253);"></span>
                                    <div class="apexcharts-tooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                <div class="apexcharts-xaxistooltip-text"
                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                            </div>
                            <div
                                class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                <div class="apexcharts-yaxistooltip-text"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Project - Able Pro</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="mb-2">
                            Release v1.2.0<span class="float-end">70%</span>
                        </p>
                        <div class="progress progress-primary" style="height: 8px">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-link-secondary">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="p-1 d-block bg-warning rounded-circle"><span
                                            class="visually-hidden">New alerts</span></span>
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <p class="mb-0 d-grid text-start">
                                        <span class="text-truncate w-100">Horizontal Layout</span>
                                    </p>
                                </div>
                                <div class="badge bg-light-secondary f-12">
                                    <i class="ti ti-paperclip text-sm"></i> 2
                                </div>
                            </div>
                        </a><a href="#" class="btn btn-link-secondary">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="p-1 d-block bg-warning rounded-circle"><span
                                            class="visually-hidden">New alerts</span></span>
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <p class="mb-0 d-grid text-start">
                                        <span class="text-truncate w-100">Invoice Generator</span>
                                    </p>
                                </div>
                            </div>
                        </a><a href="#" class="btn btn-link-secondary">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="p-1 d-block bg-warning rounded-circle"><span
                                            class="visually-hidden">New alerts</span></span>
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <p class="mb-0 d-grid text-start">
                                        <span class="text-truncate w-100">Package Upgrades</span>
                                    </p>
                                </div>
                            </div>
                        </a><a href="#" class="btn btn-link-secondary">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="p-1 d-block bg-success rounded-circle"><span
                                            class="visually-hidden">New alerts</span></span>
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <p class="mb-0 d-grid text-start">
                                        <span class="text-truncate w-100">Figma Auto Layout</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                            <i class="ti ti-plus"></i> Add task
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Project overview</h5>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ti ti-dots f-18"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                                <a class="dropdown-item" href="#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-6 col-xl-4">
                            <div class="mt-3 row align-items-center">
                                <div class="col-6">
                                    <p class="text-muted mb-1">Total Tasks</p>
                                    <h5 class="mb-0">34,686</h5>
                                </div>
                                <div class="col-6">
                                    <div id="total-tasks-graph" style="min-height: 60px;">
                                        <div id="apexchartsnb2cex8rl"
                                            class="apexcharts-canvas apexchartsnb2cex8rl apexcharts-theme-light"
                                            style="width: 83px; height: 60px;"><svg xmlns="http://www.w3.org/2000/svg"
                                                version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                class="apexcharts-svg" xmlns:data="ApexChartsNS"
                                                transform="translate(0, 0)" width="83" height="60">
                                                <foreignObject x="0" y="0" width="83" height="60">
                                                    <style type="text/css">
                                                        .apexcharts-flip-y {
                                                            transform: scaleY(-1) translateY(-100%);
                                                            transform-origin: top;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-flip-x {
                                                            transform: scaleX(-1);
                                                            transform-origin: center;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-legend {
                                                            display: flex;
                                                            overflow: auto;
                                                            padding: 0 10px;
                                                        }

                                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                            flex-direction: column;
                                                        }

                                                        .apexcharts-legend-group {
                                                            display: flex;
                                                        }

                                                        .apexcharts-legend-group-vertical {
                                                            flex-direction: column-reverse;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom,
                                                        .apexcharts-legend.apx-legend-position-top {
                                                            flex-wrap: wrap
                                                        }

                                                        .apexcharts-legend.apx-legend-position-right,
                                                        .apexcharts-legend.apx-legend-position-left {
                                                            flex-direction: column;
                                                            bottom: 0;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                        .apexcharts-legend.apx-legend-position-right,
                                                        .apexcharts-legend.apx-legend-position-left {
                                                            justify-content: flex-start;
                                                            align-items: flex-start;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                            justify-content: center;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                            justify-content: flex-end;
                                                            align-items: flex-end;
                                                        }

                                                        .apexcharts-legend-series {
                                                            cursor: pointer;
                                                            line-height: normal;
                                                            display: flex;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend-text {
                                                            position: relative;
                                                            font-size: 14px;
                                                        }

                                                        .apexcharts-legend-text *,
                                                        .apexcharts-legend-marker * {
                                                            pointer-events: none;
                                                        }

                                                        .apexcharts-legend-marker {
                                                            position: relative;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            cursor: pointer;
                                                            margin-right: 1px;
                                                        }

                                                        .apexcharts-legend-series.apexcharts-no-click {
                                                            cursor: auto;
                                                        }

                                                        .apexcharts-legend .apexcharts-hidden-zero-series,
                                                        .apexcharts-legend .apexcharts-hidden-null-series {
                                                            display: none !important;
                                                        }

                                                        .apexcharts-inactive-legend {
                                                            opacity: 0.45;
                                                        }
                                                    </style>
                                                </foreignObject>
                                                <rect width="0" height="0" x="0" y="0" rx="0"
                                                    ry="0" opacity="1" stroke-width="0" stroke="none"
                                                    stroke-dasharray="0" fill="#fefefe"></rect>
                                                <g class="apexcharts-datalabels-group"
                                                    transform="translate(0, 0) scale(1)"></g>
                                                <g class="apexcharts-datalabels-group"
                                                    transform="translate(0, 0) scale(1)"></g>
                                                <g class="apexcharts-yaxis" rel="0"
                                                    transform="translate(-18, 0)"></g>
                                                <g class="apexcharts-inner apexcharts-graphical"
                                                    transform="translate(0, 1)">
                                                    <defs>
                                                        <clipPath id="gridRectMasknb2cex8rl">
                                                            <rect width="89" height="64" x="-3" y="-3"
                                                                rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectBarMasknb2cex8rl">
                                                            <rect width="89" height="64" x="-3" y="-3"
                                                                rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectMarkerMasknb2cex8rl">
                                                            <rect width="83" height="58" x="0" y="0"
                                                                rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="forecastMasknb2cex8rl"></clipPath>
                                                        <clipPath id="nonForecastMasknb2cex8rl"></clipPath>
                                                        <linearGradient x1="0" y1="0" x2="0"
                                                            y2="1" id="SvgjsLinearGradient1005">
                                                            <stop stop-opacity="0.5" stop-color="rgba(70,128,255,0.5)"
                                                                offset="0"></stop>
                                                            <stop stop-opacity="0" stop-color="rgba(255,255,255,0)"
                                                                offset="1"></stop>
                                                            <stop stop-opacity="0" stop-color="rgba(255,255,255,0)"
                                                                offset="1"></stop>
                                                        </linearGradient>
                                                    </defs>
                                                    <line x1="0" y1="0" x2="0"
                                                        y2="58" stroke="#b6b6b6" stroke-dasharray="3"
                                                        stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                                        width="1" height="58" fill="#b1b9c4" filter="none"
                                                        fill-opacity="0.9" stroke-width="1"></line>
                                                    <g class="apexcharts-grid">
                                                        <g class="apexcharts-gridlines-horizontal"
                                                            style="display: none;">
                                                            <line x1="0" y1="0" x2="83"
                                                                y2="0" stroke="#e0e0e0" stroke-dasharray="0"
                                                                stroke-linecap="butt" class="apexcharts-gridline">
                                                            </line>
                                                            <line x1="0" y1="29" x2="83"
                                                                y2="29" stroke="#e0e0e0" stroke-dasharray="0"
                                                                stroke-linecap="butt" class="apexcharts-gridline">
                                                            </line>
                                                            <line x1="0" y1="58" x2="83"
                                                                y2="58" stroke="#e0e0e0" stroke-dasharray="0"
                                                                stroke-linecap="butt" class="apexcharts-gridline">
                                                            </line>
                                                        </g>
                                                        <g class="apexcharts-gridlines-vertical" style="display: none;">
                                                        </g>
                                                        <line x1="0" y1="58" x2="83"
                                                            y2="58" stroke="transparent" stroke-dasharray="0"
                                                            stroke-linecap="butt"></line>
                                                        <line x1="0" y1="1" x2="0"
                                                            y2="58" stroke="transparent" stroke-dasharray="0"
                                                            stroke-linecap="butt"></line>
                                                    </g>
                                                    <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                    <g class="apexcharts-area-series apexcharts-plot-series">
                                                        <g class="apexcharts-series" zIndex="0"
                                                            seriesName="series-1" data:longestSeries="true"
                                                            rel="1" data:realIndex="0">
                                                            <path
                                                                d="M0 55.1C4.841666666666667 55.1 8.991666666666667 43.5 13.833333333333334 43.5C18.675 43.5 22.825000000000003 56.26 27.666666666666668 56.26C32.50833333333333 56.26 36.65833333333333 52.2 41.5 52.2C46.34166666666667 52.2 50.49166666666667 55.68 55.333333333333336 55.68C60.175000000000004 55.68 64.325 28.999999999999996 69.16666666666667 28.999999999999996C74.00833333333334 28.999999999999996 78.15833333333333 58 83 58C83 58 83 58 83 58L0 58C0 58 0 55.1 0 55.1 "
                                                                fill="url(#SvgjsLinearGradient1005)" fill-opacity="1"
                                                                stroke="none" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="0"
                                                                stroke-dasharray="0" class="apexcharts-area"
                                                                index="0" clip-path="url(#gridRectMasknb2cex8rl)"
                                                                pathTo="M 0 55.1C 4.841666666666667 55.1 8.991666666666667 43.5 13.833333333333334 43.5C 18.675 43.5 22.825000000000003 56.26 27.666666666666668 56.26C 32.50833333333333 56.26 36.65833333333333 52.2 41.5 52.2C 46.34166666666667 52.2 50.49166666666667 55.68 55.333333333333336 55.68C 60.175000000000004 55.68 64.325 28.999999999999996 69.16666666666667 28.999999999999996C 74.00833333333334 28.999999999999996 78.15833333333333 58 83 58C 83 58 83 58 83 58 L 0 58z"
                                                                pathFrom="M 0 58 L 0 58 L 13.833333333333334 58 L 27.666666666666668 58 L 41.5 58 L 55.333333333333336 58 L 69.16666666666667 58 L 83 58z">
                                                            </path>
                                                            <path
                                                                d="M0 55.1C4.841666666666667 55.1 8.991666666666667 43.5 13.833333333333334 43.5C18.675 43.5 22.825000000000003 56.26 27.666666666666668 56.26C32.50833333333333 56.26 36.65833333333333 52.2 41.5 52.2C46.34166666666667 52.2 50.49166666666667 55.68 55.333333333333336 55.68C60.175000000000004 55.68 64.325 28.999999999999996 69.16666666666667 28.999999999999996C74.00833333333334 28.999999999999996 78.15833333333333 58 83 58C83 58 83 58 83 58 "
                                                                fill="none" fill-opacity="1" stroke="#4680ff"
                                                                stroke-opacity="1" stroke-linecap="butt"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                class="apexcharts-area" index="0"
                                                                clip-path="url(#gridRectMasknb2cex8rl)"
                                                                pathTo="M 0 55.1C 4.841666666666667 55.1 8.991666666666667 43.5 13.833333333333334 43.5C 18.675 43.5 22.825000000000003 56.26 27.666666666666668 56.26C 32.50833333333333 56.26 36.65833333333333 52.2 41.5 52.2C 46.34166666666667 52.2 50.49166666666667 55.68 55.333333333333336 55.68C 60.175000000000004 55.68 64.325 28.999999999999996 69.16666666666667 28.999999999999996C 74.00833333333334 28.999999999999996 78.15833333333333 58 83 58"
                                                                pathFrom="M 0 58 L 0 58 L 13.833333333333334 58 L 27.666666666666668 58 L 41.5 58 L 55.333333333333336 58 L 69.16666666666667 58 L 83 58"
                                                                fill-rule="evenodd"></path>
                                                            <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                                data:realIndex="0">
                                                                <g class="apexcharts-series-markers">
                                                                    <path d="M 0, 0
               m -0, 0
               a 0,0 0 1,0 0,0
               a 0,0 0 1,0 -0,0" fill="#4680ff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                        stroke-linecap="butt" stroke-width="2"
                                                                        stroke-dasharray="0" cx="0"
                                                                        cy="0" shape="circle"
                                                                        class="apexcharts-marker w8wyzmrdf no-pointer-events"
                                                                        default-marker-size="0"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                    </g>
                                                    <line x1="0" y1="0" x2="83"
                                                        y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                        stroke-width="1" stroke-linecap="butt"
                                                        class="apexcharts-ycrosshairs"></line>
                                                    <line x1="0" y1="0" x2="83"
                                                        y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                        stroke-width="0" stroke-linecap="butt"
                                                        class="apexcharts-ycrosshairs-hidden"></line>
                                                    <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                        <g class="apexcharts-xaxis-texts-g"
                                                            transform="translate(0, -4)"></g>
                                                    </g>
                                                    <g
                                                        class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown">
                                                    </g>
                                                    <g
                                                        class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown">
                                                    </g>
                                                    <g
                                                        class="apexcharts-point-annotations apexcharts-hidden-element-shown">
                                                    </g>
                                                </g>
                                            </svg>
                                            <div class="apexcharts-legend" style="max-height: 30px;"></div>
                                            <div class="apexcharts-tooltip apexcharts-theme-light">
                                                <div class="apexcharts-tooltip-title"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                </div>
                                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                    style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                        shape="circle" style="color: rgb(70, 128, 255);"></span>
                                                    <div class="apexcharts-tooltip-text"
                                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                        <div class="apexcharts-tooltip-y-group"><span
                                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                                        <div class="apexcharts-tooltip-goals-group"><span
                                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                                        <div class="apexcharts-tooltip-z-group"><span
                                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                <div class="apexcharts-yaxistooltip-text"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="mt-3 row align-items-center">
                                <div class="col-6">
                                    <p class="text-muted mb-1">Pending Tasks</p>
                                    <h5 class="mb-0">3,786</h5>
                                </div>
                                <div class="col-6">
                                    <div id="pending-tasks-graph" style="min-height: 60px;">
                                        <div id="apexchartswqbe88oz"
                                            class="apexcharts-canvas apexchartswqbe88oz apexcharts-theme-light"
                                            style="width: 83px; height: 60px;"><svg xmlns="http://www.w3.org/2000/svg"
                                                version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                class="apexcharts-svg" xmlns:data="ApexChartsNS"
                                                transform="translate(0, 0)" width="83" height="60">
                                                <foreignObject x="0" y="0" width="83" height="60">
                                                    <style type="text/css">
                                                        .apexcharts-flip-y {
                                                            transform: scaleY(-1) translateY(-100%);
                                                            transform-origin: top;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-flip-x {
                                                            transform: scaleX(-1);
                                                            transform-origin: center;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-legend {
                                                            display: flex;
                                                            overflow: auto;
                                                            padding: 0 10px;
                                                        }

                                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                            flex-direction: column;
                                                        }

                                                        .apexcharts-legend-group {
                                                            display: flex;
                                                        }

                                                        .apexcharts-legend-group-vertical {
                                                            flex-direction: column-reverse;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom,
                                                        .apexcharts-legend.apx-legend-position-top {
                                                            flex-wrap: wrap
                                                        }

                                                        .apexcharts-legend.apx-legend-position-right,
                                                        .apexcharts-legend.apx-legend-position-left {
                                                            flex-direction: column;
                                                            bottom: 0;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                        .apexcharts-legend.apx-legend-position-right,
                                                        .apexcharts-legend.apx-legend-position-left {
                                                            justify-content: flex-start;
                                                            align-items: flex-start;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                            justify-content: center;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                            justify-content: flex-end;
                                                            align-items: flex-end;
                                                        }

                                                        .apexcharts-legend-series {
                                                            cursor: pointer;
                                                            line-height: normal;
                                                            display: flex;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend-text {
                                                            position: relative;
                                                            font-size: 14px;
                                                        }

                                                        .apexcharts-legend-text *,
                                                        .apexcharts-legend-marker * {
                                                            pointer-events: none;
                                                        }

                                                        .apexcharts-legend-marker {
                                                            position: relative;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            cursor: pointer;
                                                            margin-right: 1px;
                                                        }

                                                        .apexcharts-legend-series.apexcharts-no-click {
                                                            cursor: auto;
                                                        }

                                                        .apexcharts-legend .apexcharts-hidden-zero-series,
                                                        .apexcharts-legend .apexcharts-hidden-null-series {
                                                            display: none !important;
                                                        }

                                                        .apexcharts-inactive-legend {
                                                            opacity: 0.45;
                                                        }
                                                    </style>
                                                </foreignObject>
                                                <rect width="0" height="0" x="0" y="0" rx="0"
                                                    ry="0" opacity="1" stroke-width="0" stroke="none"
                                                    stroke-dasharray="0" fill="#fefefe"></rect>
                                                <g class="apexcharts-datalabels-group"
                                                    transform="translate(0, 0) scale(1)"></g>
                                                <g class="apexcharts-datalabels-group"
                                                    transform="translate(0, 0) scale(1)"></g>
                                                <g class="apexcharts-yaxis" rel="0"
                                                    transform="translate(-18, 0)"></g>
                                                <g class="apexcharts-inner apexcharts-graphical"
                                                    transform="translate(0, 1)">
                                                    <defs>
                                                        <clipPath id="gridRectMaskwqbe88oz">
                                                            <rect width="89" height="64" x="-3" y="-3"
                                                                rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectBarMaskwqbe88oz">
                                                            <rect width="89" height="64" x="-3" y="-3"
                                                                rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectMarkerMaskwqbe88oz">
                                                            <rect width="83" height="58" x="0" y="0"
                                                                rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="forecastMaskwqbe88oz"></clipPath>
                                                        <clipPath id="nonForecastMaskwqbe88oz"></clipPath>
                                                        <linearGradient x1="0" y1="0" x2="0"
                                                            y2="1" id="SvgjsLinearGradient1006">
                                                            <stop stop-opacity="0.5" stop-color="rgba(220,38,38,0.5)"
                                                                offset="0"></stop>
                                                            <stop stop-opacity="0" stop-color="rgba(255,255,255,0)"
                                                                offset="1"></stop>
                                                            <stop stop-opacity="0" stop-color="rgba(255,255,255,0)"
                                                                offset="1"></stop>
                                                        </linearGradient>
                                                    </defs>
                                                    <line x1="0" y1="0" x2="0"
                                                        y2="58" stroke="#b6b6b6" stroke-dasharray="3"
                                                        stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                                        width="1" height="58" fill="#b1b9c4" filter="none"
                                                        fill-opacity="0.9" stroke-width="1"></line>
                                                    <g class="apexcharts-grid">
                                                        <g class="apexcharts-gridlines-horizontal"
                                                            style="display: none;">
                                                            <line x1="0" y1="0" x2="83"
                                                                y2="0" stroke="#e0e0e0" stroke-dasharray="0"
                                                                stroke-linecap="butt" class="apexcharts-gridline">
                                                            </line>
                                                            <line x1="0" y1="29" x2="83"
                                                                y2="29" stroke="#e0e0e0" stroke-dasharray="0"
                                                                stroke-linecap="butt" class="apexcharts-gridline">
                                                            </line>
                                                            <line x1="0" y1="58" x2="83"
                                                                y2="58" stroke="#e0e0e0" stroke-dasharray="0"
                                                                stroke-linecap="butt" class="apexcharts-gridline">
                                                            </line>
                                                        </g>
                                                        <g class="apexcharts-gridlines-vertical" style="display: none;">
                                                        </g>
                                                        <line x1="0" y1="58" x2="83"
                                                            y2="58" stroke="transparent" stroke-dasharray="0"
                                                            stroke-linecap="butt"></line>
                                                        <line x1="0" y1="1" x2="0"
                                                            y2="58" stroke="transparent" stroke-dasharray="0"
                                                            stroke-linecap="butt"></line>
                                                    </g>
                                                    <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                    <g class="apexcharts-area-series apexcharts-plot-series">
                                                        <g class="apexcharts-series" zIndex="0"
                                                            seriesName="series-1" data:longestSeries="true"
                                                            rel="1" data:realIndex="0">
                                                            <path
                                                                d="M0 58C4.841666666666667 58 8.991666666666667 28.999999999999996 13.833333333333334 28.999999999999996C18.675 28.999999999999996 22.825000000000003 55.68 27.666666666666668 55.68C32.50833333333333 55.68 36.65833333333333 52.2 41.5 52.2C46.34166666666667 52.2 50.49166666666667 56.26 55.333333333333336 56.26C60.175000000000004 56.26 64.325 43.5 69.16666666666667 43.5C74.00833333333334 43.5 78.15833333333333 55.1 83 55.1C83 55.1 83 55.1 83 58L0 58C0 58 0 58 0 58 "
                                                                fill="url(#SvgjsLinearGradient1006)" fill-opacity="1"
                                                                stroke="none" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="0"
                                                                stroke-dasharray="0" class="apexcharts-area"
                                                                index="0" clip-path="url(#gridRectMaskwqbe88oz)"
                                                                pathTo="M 0 58C 4.841666666666667 58 8.991666666666667 28.999999999999996 13.833333333333334 28.999999999999996C 18.675 28.999999999999996 22.825000000000003 55.68 27.666666666666668 55.68C 32.50833333333333 55.68 36.65833333333333 52.2 41.5 52.2C 46.34166666666667 52.2 50.49166666666667 56.26 55.333333333333336 56.26C 60.175000000000004 56.26 64.325 43.5 69.16666666666667 43.5C 74.00833333333334 43.5 78.15833333333333 55.1 83 55.1C 83 55.1 83 55.1 83 58 L 0 58z"
                                                                pathFrom="M 0 58 L 0 58 L 13.833333333333334 58 L 27.666666666666668 58 L 41.5 58 L 55.333333333333336 58 L 69.16666666666667 58 L 83 58z">
                                                            </path>
                                                            <path
                                                                d="M0 58C4.841666666666667 58 8.991666666666667 28.999999999999996 13.833333333333334 28.999999999999996C18.675 28.999999999999996 22.825000000000003 55.68 27.666666666666668 55.68C32.50833333333333 55.68 36.65833333333333 52.2 41.5 52.2C46.34166666666667 52.2 50.49166666666667 56.26 55.333333333333336 56.26C60.175000000000004 56.26 64.325 43.5 69.16666666666667 43.5C74.00833333333334 43.5 78.15833333333333 55.1 83 55.1C83 55.1 83 55.1 83 55.1 "
                                                                fill="none" fill-opacity="1" stroke="#dc2626"
                                                                stroke-opacity="1" stroke-linecap="butt"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                class="apexcharts-area" index="0"
                                                                clip-path="url(#gridRectMaskwqbe88oz)"
                                                                pathTo="M 0 58C 4.841666666666667 58 8.991666666666667 28.999999999999996 13.833333333333334 28.999999999999996C 18.675 28.999999999999996 22.825000000000003 55.68 27.666666666666668 55.68C 32.50833333333333 55.68 36.65833333333333 52.2 41.5 52.2C 46.34166666666667 52.2 50.49166666666667 56.26 55.333333333333336 56.26C 60.175000000000004 56.26 64.325 43.5 69.16666666666667 43.5C 74.00833333333334 43.5 78.15833333333333 55.1 83 55.1"
                                                                pathFrom="M 0 58 L 0 58 L 13.833333333333334 58 L 27.666666666666668 58 L 41.5 58 L 55.333333333333336 58 L 69.16666666666667 58 L 83 58"
                                                                fill-rule="evenodd"></path>
                                                            <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                                data:realIndex="0">
                                                                <g class="apexcharts-series-markers">
                                                                    <path d="M 0, 0
               m -0, 0
               a 0,0 0 1,0 0,0
               a 0,0 0 1,0 -0,0" fill="#dc2626" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                        stroke-linecap="butt" stroke-width="2"
                                                                        stroke-dasharray="0" cx="0"
                                                                        cy="0" shape="circle"
                                                                        class="apexcharts-marker wx8do67fjf no-pointer-events"
                                                                        default-marker-size="0"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                    </g>
                                                    <line x1="0" y1="0" x2="83"
                                                        y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                        stroke-width="1" stroke-linecap="butt"
                                                        class="apexcharts-ycrosshairs"></line>
                                                    <line x1="0" y1="0" x2="83"
                                                        y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                        stroke-width="0" stroke-linecap="butt"
                                                        class="apexcharts-ycrosshairs-hidden"></line>
                                                    <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                        <g class="apexcharts-xaxis-texts-g"
                                                            transform="translate(0, -4)"></g>
                                                    </g>
                                                    <g
                                                        class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown">
                                                    </g>
                                                    <g
                                                        class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown">
                                                    </g>
                                                    <g
                                                        class="apexcharts-point-annotations apexcharts-hidden-element-shown">
                                                    </g>
                                                </g>
                                            </svg>
                                            <div class="apexcharts-legend" style="max-height: 30px;"></div>
                                            <div class="apexcharts-tooltip apexcharts-theme-light">
                                                <div class="apexcharts-tooltip-title"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                </div>
                                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                    style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                        shape="circle" style="color: rgb(220, 38, 38);"></span>
                                                    <div class="apexcharts-tooltip-text"
                                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                        <div class="apexcharts-tooltip-y-group"><span
                                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                                        <div class="apexcharts-tooltip-goals-group"><span
                                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                                        <div class="apexcharts-tooltip-z-group"><span
                                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                <div class="apexcharts-yaxistooltip-text"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="mt-3 d-grid">
                                <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                    <i class="ti ti-plus"></i> Add project
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s bg-light-primary">
                                <i class="ti ti-at f-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Able pro</h6>
                            <small class="text-muted">@ableprodevelop</small>
                        </div>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ti ti-dots-vertical f-18"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                                <a class="dropdown-item" href="#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4">
                        <div class="user-group able-user-group">
                            <img src="../assets/images/user/avatar-1.jpg" alt="user-image" class="avtar">
                            <img src="../assets/images/user/avatar-3.jpg" alt="user-image" class="avtar">
                            <img src="../assets/images/user/avatar-4.jpg" alt="user-image" class="avtar">
                            <img src="../assets/images/user/avatar-5.jpg" alt="user-image" class="avtar">
                            <span class="avtar bg-light-primary text-primary text-sm">+2</span>
                        </div>
                        <a href="#" class="avtar avtar-s btn btn-primary rounded-circle"><i
                                class="ti ti-plus f-20"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body border-bottom pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Transactions</h5>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ti ti-dots-vertical f-18"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                                <a class="dropdown-item" href="#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="analytics-tab-1" data-bs-toggle="tab"
                                data-bs-target="#analytics-tab-1-pane" type="button" role="tab"
                                aria-controls="analytics-tab-1-pane" aria-selected="true">
                                All Transaction
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="analytics-tab-2" data-bs-toggle="tab"
                                data-bs-target="#analytics-tab-2-pane" type="button" role="tab"
                                aria-controls="analytics-tab-2-pane" aria-selected="false" tabindex="-1">
                                Success
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="analytics-tab-3" data-bs-toggle="tab"
                                data-bs-target="#analytics-tab-3-pane" type="button" role="tab"
                                aria-controls="analytics-tab-3-pane" aria-selected="false" tabindex="-1">
                                Pending
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel"
                        aria-labelledby="analytics-tab-1" tabindex="0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border">AI</div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Apple Inc.</h6>
                                                <p class="text-muted mb-0">
                                                    <small>#ABLE-PRO-T00232</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">$210,000</h6>
                                                <p class="text-danger mb-0">
                                                    <i class="ti ti-arrow-down-left"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border" data-bs-toggle="tooltip"
                                            data-bs-title="10,000 Tracks">
                                            <span>SM</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Spotify Music</h6>
                                                <p class="text-muted mb-0">
                                                    <small>#ABLE-PRO-T10232</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">- 10,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 30.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border bg-light-primary" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>MD</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Medium</h6>
                                                <p class="text-muted mb-0">
                                                    <small>06:30 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">-26</h6>
                                                <p class="text-warning mb-0">
                                                    <i class="ti ti-arrows-left-right"></i> 5%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>U</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Uber</h6>
                                                <p class="text-muted mb-0">
                                                    <small>08:40 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">+210,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border bg-light-warning" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>OC</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Ola Cabs</h6>
                                                <p class="text-muted mb-0">
                                                    <small>07:40 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">+210,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="analytics-tab-2-pane" role="tabpanel"
                        aria-labelledby="analytics-tab-2" tabindex="0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>U</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Uber</h6>
                                                <p class="text-muted mb-0">
                                                    <small>08:40 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">+210,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border bg-light-warning" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>OC</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Ola Cabs</h6>
                                                <p class="text-muted mb-0">
                                                    <small>07:40 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">+210,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border">AI</div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Apple Inc.</h6>
                                                <p class="text-muted mb-0">
                                                    <small>#ABLE-PRO-T00232</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">$210,000</h6>
                                                <p class="text-danger mb-0">
                                                    <i class="ti ti-arrow-down-left"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border" data-bs-toggle="tooltip"
                                            data-bs-title="10,000 Tracks">
                                            <span>SM</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Spotify Music</h6>
                                                <p class="text-muted mb-0">
                                                    <small>#ABLE-PRO-T10232</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">- 10,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 30.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border bg-light-primary" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>MD</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Medium</h6>
                                                <p class="text-muted mb-0">
                                                    <small>06:30 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">-26</h6>
                                                <p class="text-warning mb-0">
                                                    <i class="ti ti-arrows-left-right"></i> 5%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="analytics-tab-3-pane" role="tabpanel"
                        aria-labelledby="analytics-tab-3" tabindex="0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border" data-bs-toggle="tooltip"
                                            data-bs-title="10,000 Tracks">
                                            <span>SM</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Spotify Music</h6>
                                                <p class="text-muted mb-0">
                                                    <small>#ABLE-PRO-T10232</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">- 10,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 30.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border bg-light-primary" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>MD</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Medium</h6>
                                                <p class="text-muted mb-0">
                                                    <small>06:30 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">-26</h6>
                                                <p class="text-warning mb-0">
                                                    <i class="ti ti-arrows-left-right"></i> 5%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>U</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Uber</h6>
                                                <p class="text-muted mb-0">
                                                    <small>08:40 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">+210,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border">AI</div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Apple Inc.</h6>
                                                <p class="text-muted mb-0">
                                                    <small>#ABLE-PRO-T00232</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">$210,000</h6>
                                                <p class="text-danger mb-0">
                                                    <i class="ti ti-arrow-down-left"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s border bg-light-warning" data-bs-toggle="tooltip"
                                            data-bs-title="143 Posts">
                                            <span>OC</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <h6 class="mb-0">Ola Cabs</h6>
                                                <p class="text-muted mb-0">
                                                    <small>07:40 pm</small>
                                                </p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">+210,000</h6>
                                                <p class="text-success mb-0">
                                                    <i class="ti ti-arrow-up-right"></i> 10.6%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-secondary d-grid">
                                    <span class="text-truncate w-100">View all Transaction History</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-primary d-grid">
                                    <span class="text-truncate w-100">Create new Transaction</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Total Income</h5>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ti ti-dots-vertical f-18"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                                <a class="dropdown-item" href="#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <div id="total-income-graph" style="min-height: 323px;">
                        <div id="apexchartsycr57fps" class="apexcharts-canvas apexchartsycr57fps apexcharts-theme-light"
                            style="width: 406px; height: 323px;"><svg xmlns="http://www.w3.org/2000/svg"
                                version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="406" height="323">
                                <foreignObject x="0" y="0" width="406" height="323">
                                    <style type="text/css">
                                        .apexcharts-flip-y {
                                            transform: scaleY(-1) translateY(-100%);
                                            transform-origin: top;
                                            transform-box: fill-box;
                                        }

                                        .apexcharts-flip-x {
                                            transform: scaleX(-1);
                                            transform-origin: center;
                                            transform-box: fill-box;
                                        }

                                        .apexcharts-legend {
                                            display: flex;
                                            overflow: auto;
                                            padding: 0 10px;
                                        }

                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                            flex-direction: column;
                                        }

                                        .apexcharts-legend-group {
                                            display: flex;
                                        }

                                        .apexcharts-legend-group-vertical {
                                            flex-direction: column-reverse;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom,
                                        .apexcharts-legend.apx-legend-position-top {
                                            flex-wrap: wrap
                                        }

                                        .apexcharts-legend.apx-legend-position-right,
                                        .apexcharts-legend.apx-legend-position-left {
                                            flex-direction: column;
                                            bottom: 0;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                        .apexcharts-legend.apx-legend-position-right,
                                        .apexcharts-legend.apx-legend-position-left {
                                            justify-content: flex-start;
                                            align-items: flex-start;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                            justify-content: center;
                                            align-items: center;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                            justify-content: flex-end;
                                            align-items: flex-end;
                                        }

                                        .apexcharts-legend-series {
                                            cursor: pointer;
                                            line-height: normal;
                                            display: flex;
                                            align-items: center;
                                        }

                                        .apexcharts-legend-text {
                                            position: relative;
                                            font-size: 14px;
                                        }

                                        .apexcharts-legend-text *,
                                        .apexcharts-legend-marker * {
                                            pointer-events: none;
                                        }

                                        .apexcharts-legend-marker {
                                            position: relative;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            cursor: pointer;
                                            margin-right: 1px;
                                        }

                                        .apexcharts-legend-series.apexcharts-no-click {
                                            cursor: auto;
                                        }

                                        .apexcharts-legend .apexcharts-hidden-zero-series,
                                        .apexcharts-legend .apexcharts-hidden-null-series {
                                            display: none !important;
                                        }

                                        .apexcharts-inactive-legend {
                                            opacity: 0.45;
                                        }
                                    </style>
                                </foreignObject>
                                <g class="apexcharts-inner apexcharts-graphical" transform="translate(43, 0)">
                                    <defs>
                                        <clipPath id="gridRectMaskycr57fps">
                                            <rect width="326" height="326" x="-3" y="-3" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="gridRectBarMaskycr57fps">
                                            <rect width="326" height="326" x="-3" y="-3" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="gridRectMarkerMaskycr57fps">
                                            <rect width="320" height="320" x="0" y="0" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="forecastMaskycr57fps"></clipPath>
                                        <clipPath id="nonForecastMaskycr57fps"></clipPath>
                                    </defs>
                                    <g class="apexcharts-pie">
                                        <g transform="translate(0, 0) scale(1)">
                                            <circle r="97.56341463414635" cx="160" cy="160"
                                                fill="transparent"></circle>
                                            <g class="apexcharts-slices">
                                                <g class="apexcharts-series apexcharts-pie-series"
                                                    seriesName="Totalxincome" rel="1" data:realIndex="0">
                                                    <path
                                                        d="M 160 9.902439024390219 A 150.09756097560978 150.09756097560978 0 0 1 299.4371398476228 215.5568343405325 L 250.63414090095483 196.11194232134613 A 97.56341463414635 97.56341463414635 0 0 0 160 62.436585365853645 L 160 9.902439024390219 z "
                                                        fill="rgba(70,128,255,1)" fill-opacity="1 1 1 0.3"
                                                        stroke="#ffffff" stroke-opacity="1" stroke-linecap="butt"
                                                        stroke-width="2" stroke-dasharray="0"
                                                        class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                        index="0" j="0" data:angle="111.72413793103448"
                                                        data:startAngle="0" data:strokeWidth="2" data:value="27"
                                                        data:pathOrig="M 160 9.902439024390219 A 150.09756097560978 150.09756097560978 0 0 1 299.4371398476228 215.5568343405325 L 250.63414090095483 196.11194232134613 A 97.56341463414635 97.56341463414635 0 0 0 160 62.436585365853645 L 160 9.902439024390219 z ">
                                                    </path>
                                                </g>
                                                <g class="apexcharts-series apexcharts-pie-series"
                                                    seriesName="Totalxrent" rel="2" data:realIndex="1">
                                                    <path
                                                        d="M 299.4371398476228 215.5568343405325 A 150.09756097560978 150.09756097560978 0 0 1 92.09870975109261 293.86072087569426 L 115.8641613382102 247.00946856920126 A 97.56341463414635 97.56341463414635 0 0 0 250.63414090095483 196.11194232134613 L 299.4371398476228 215.5568343405325 z "
                                                        fill="rgba(229,138,0,1)" fill-opacity="1 1 1 0.3"
                                                        stroke="#ffffff" stroke-opacity="1" stroke-linecap="butt"
                                                        stroke-width="2" stroke-dasharray="0"
                                                        class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                        index="0" j="1" data:angle="95.17241379310343"
                                                        data:startAngle="111.72413793103448" data:strokeWidth="2"
                                                        data:value="23"
                                                        data:pathOrig="M 299.4371398476228 215.5568343405325 A 150.09756097560978 150.09756097560978 0 0 1 92.09870975109261 293.86072087569426 L 115.8641613382102 247.00946856920126 A 97.56341463414635 97.56341463414635 0 0 0 250.63414090095483 196.11194232134613 L 299.4371398476228 215.5568343405325 z ">
                                                    </path>
                                                </g>
                                                <g class="apexcharts-series apexcharts-pie-series" seriesName="Download"
                                                    rel="3" data:realIndex="2">
                                                    <path
                                                        d="M 92.09870975109261 293.86072087569426 A 150.09756097560978 150.09756097560978 0 0 1 18.648024075585056 109.51340065828842 L 68.12121564913029 127.18371042788746 A 97.56341463414635 97.56341463414635 0 0 0 115.8641613382102 247.00946856920126 L 92.09870975109261 293.86072087569426 z "
                                                        fill="rgba(44,168,127,1)" fill-opacity="1 1 1 0.3"
                                                        stroke="#ffffff" stroke-opacity="1" stroke-linecap="butt"
                                                        stroke-width="2" stroke-dasharray="0"
                                                        class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                        index="0" j="2" data:angle="82.75862068965517"
                                                        data:startAngle="206.8965517241379" data:strokeWidth="2"
                                                        data:value="20"
                                                        data:pathOrig="M 92.09870975109261 293.86072087569426 A 150.09756097560978 150.09756097560978 0 0 1 18.648024075585056 109.51340065828842 L 68.12121564913029 127.18371042788746 A 97.56341463414635 97.56341463414635 0 0 0 115.8641613382102 247.00946856920126 L 92.09870975109261 293.86072087569426 z ">
                                                    </path>
                                                </g>
                                                <g class="apexcharts-series apexcharts-pie-series" seriesName="Views"
                                                    rel="4" data:realIndex="3">
                                                    <path
                                                        d="M 18.648024075585056 109.51340065828842 A 150.09756097560978 150.09756097560978 0 0 1 159.9738030337506 9.902441310506788 L 159.9829719719379 62.43658685182942 A 97.56341463414635 97.56341463414635 0 0 0 68.12121564913029 127.18371042788746 L 18.648024075585056 109.51340065828842 z "
                                                        fill="rgba(70,128,255,0.3)" fill-opacity="1 1 1 0.3"
                                                        stroke="#ffffff" stroke-opacity="1" stroke-linecap="butt"
                                                        stroke-width="2" stroke-dasharray="0"
                                                        class="apexcharts-pie-area apexcharts-donut-slice-3"
                                                        index="0" j="3" data:angle="70.34482758620692"
                                                        data:startAngle="289.6551724137931" data:strokeWidth="2"
                                                        data:value="17"
                                                        data:pathOrig="M 18.648024075585056 109.51340065828842 A 150.09756097560978 150.09756097560978 0 0 1 159.9738030337506 9.902441310506788 L 159.9829719719379 62.43658685182942 A 97.56341463414635 97.56341463414635 0 0 0 68.12121564913029 127.18371042788746 L 18.648024075585056 109.51340065828842 z ">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                            <text x="160" y="150" text-anchor="middle" dominant-baseline="auto"
                                                font-size="16px" font-family="Helvetica, Arial, sans-serif"
                                                font-weight="600" fill="#4680ff"
                                                class="apexcharts-text apexcharts-datalabel-label"
                                                style="font-family: Helvetica, Arial, sans-serif;"></text><text x="160"
                                                y="186" text-anchor="middle" dominant-baseline="auto" font-size="20px"
                                                font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                fill="#373d3f" class="apexcharts-text apexcharts-datalabel-value"
                                                style="font-family: Helvetica, Arial, sans-serif;"></text></g>
                                    </g>
                                    <line x1="0" y1="0" x2="320" y2="0"
                                        stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                        class="apexcharts-ycrosshairs"></line>
                                    <line x1="0" y1="0" x2="320" y2="0"
                                        stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                        class="apexcharts-ycrosshairs-hidden"></line>
                                </g>
                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                            </svg>
                            <div class="apexcharts-legend"></div>
                            <div class="apexcharts-tooltip apexcharts-theme-dark">
                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                    style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle"
                                        style="background-color: rgb(70, 128, 255);"></span>
                                    <div class="apexcharts-tooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                    style="order: 2;"><span class="apexcharts-tooltip-marker" shape="circle"
                                        style="background-color: rgb(229, 138, 0);"></span>
                                    <div class="apexcharts-tooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-2"
                                    style="order: 3;"><span class="apexcharts-tooltip-marker" shape="circle"
                                        style="background-color: rgb(44, 168, 127);"></span>
                                    <div class="apexcharts-tooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-3"
                                    style="order: 4;"><span class="apexcharts-tooltip-marker" shape="circle"
                                        style="background-color: rgb(70, 128, 255);"></span>
                                    <div class="apexcharts-tooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-sm-6">
                            <div class="bg-body p-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <span class="p-1 d-block bg-primary rounded-circle"><span
                                                class="visually-hidden">New alerts</span></span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-0">Income</p>
                                    </div>
                                </div>
                                <h6 class="mb-0">
                                    $23,876
                                    <small class="text-muted"><i class="ti ti-chevrons-up"></i> +$763,43</small>
                                </h6>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-body p-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <span class="p-1 d-block bg-warning rounded-circle"><span
                                                class="visually-hidden">New alerts</span></span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-0">Rent</p>
                                    </div>
                                </div>
                                <h6 class="mb-0">
                                    $23,876
                                    <small class="text-muted"><i class="ti ti-chevrons-up"></i> +$763,43</small>
                                </h6>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-body p-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <span class="p-1 d-block bg-success rounded-circle"><span
                                                class="visually-hidden">New alerts</span></span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-0">Download</p>
                                    </div>
                                </div>
                                <h6 class="mb-0">
                                    $23,876
                                    <small class="text-muted"><i class="ti ti-chevrons-up"></i> +$763,43</small>
                                </h6>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-body p-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <span class="p-1 d-block bg-light-primary rounded-circle"><span
                                                class="visually-hidden">New alerts</span></span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-0">Views</p>
                                    </div>
                                </div>
                                <h6 class="mb-0">
                                    $23,876
                                    <small class="text-muted"><i class="ti ti-chevrons-up"></i> +$763,43</small>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
