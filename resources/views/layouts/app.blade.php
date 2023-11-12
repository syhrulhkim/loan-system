<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- CSS -->
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Inter", sans-serif;
        }

        .item1 { 
            grid-area: first;
            padding: 24px;
        }
        .item2 { 
            grid-area: second; 
        }
        .grid-container {
            display: grid;
            grid-template-areas: 'first second';
            gap: 10px;
            padding: 10px;
        }
        .formbold-main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            align-self: start;
        }

        .formbold-form-wrapper {
            margin: 0 auto;
            max-width: 550px;
            width: 100%;
            /* background: white; */
        }

        .formbold-event-details {
            background: #fafafa;
            border: 1px solid #dde3ec;
            border-radius: 5px;
            margin: 25px 20px 30px 20px;
        }
        .formbold-event-details h5 {
            color: #07074d;
            font-weight: 600;
            font-size: 18px;
            line-height: 24px;
            padding: 15px 25px;
        }
        .formbold-event-details ul {
            border-top: 1px solid #edeef2;
            padding: 25px;
            margin: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            row-gap: 14px;
        }
        .formbold-event-details ul li {
            color: #536387;
            font-size: 16px;
            line-height: 24px;
            width: 50%;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .formbold-input-flex {
            display: flex;
            gap: 20px;
            margin-bottom: 12px;
        }
        .formbold-input-flex > div {
            width: 50%;
            display: flex;
            flex-direction: column-reverse;
        }
        .formbold-textarea {
            display: flex;
            flex-direction: column-reverse;
        }

        .formbold-form-input {
            width: 100%;
            padding-bottom: 10px;
            border: none;
            border-bottom: 1px solid #DDE3EC;
            background: #FFFFFF;
            font-weight: 500;
            font-size: 16px;
            color: #07074D;
            outline: none;
            resize: none;
            border-radius: 8px
        }
        .formbold-form-input::placeholder {
            color: #536387;
        }
        .formbold-form-input:focus {
            border-color: #6A64F1;
        }
        .formbold-form-label {
            color: white;
            font-weight: 500;
            font-size: 14px;
            line-height: 14px;
            display: block;
            margin-bottom: 8px;
        }
        .formbold-form-input:focus + .formbold-form-label {
            color: #6A64F1;
        }

        .formbold-input-file {
            margin-top: 30px;
        }
        .formbold-input-file input[type="file"] {
            position: absolute;
            top: 6px;
            left: 0;
            z-index: -1;
        }
        .formbold-input-file .formbold-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .formbold-filename-wrapper {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 22px;
        }
        .formbold-filename {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            line-height: 24px;
            color: #536387;
        }
        .formbold-filename svg {
            cursor: pointer;
        }

        .formbold-btn {
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            padding: 12px 25px;
            border: none;
            font-weight: 500;
            background-color: #6A64F1;
            color: white;
            cursor: pointer;
            margin-top: 10px;
        }
        .formbold-btn:hover {
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }
        .formbold-input-flex-full-width {
            margin-bottom: 22px;
            width: 100%;
        }

        /* Option Radio */
        .formbold-mb-5 {
            margin-bottom: 20px;
        }
        .formbold-radio-flex {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
            z-index: 1000; /* Set a high z-index to make sure it's on top of other elements */
        }

        /* Style the modal content */
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow for depth */
            z-index: 1001; /* Set a higher z-index than the modal background */
        }

        /* Style the close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .list-row {
            
        }
        .list-col {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
            color: black;
            padding: 10px;
            text-align: center;
            border-top: 1px solid black;
        }
        .pay:hover {
            border-radius: 5px;
            border: 1px solid black;
            padding: 0px 10px;
        }
        .button-to-pay {
            background-color: white;
            border: 1px solid white;
            border-radius: 5px;
            color: black;
            width: 100%;
        }
        .button-to-pay:hover {
            background-color: green;
            border: 1px solid white;
            border-radius: 5px;
            color: white;
            width: 100%;
        }

        /* Disabled button */
        #myButton[disabled] {
            background-color: grey;
            cursor: not-allowed; /* Change cursor style for better user experience */
        }
    </style>
</html>
