<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>{{ $siteConfig['title'] }}</title>
    <meta name="description" content="{{ $siteConfig['description'] }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/reset.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/site.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic//container.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/grid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/header.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/image.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/menu.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/divider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/segment.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/input.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/button.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/list.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/message.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/semantic/components/icon.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/common/humane/themes/original.css') }}">

    <style type="text/css">
        body {
            background-color: #3f51b5;
        }
        body > .grid {
            height: 100%;
        }
        .image {
            margin-top: -100px;
        }
        .column {
            max-width: 450px;
        }
    </style>
</head>