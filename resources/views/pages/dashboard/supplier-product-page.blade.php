@extends('layout.sidenav-layout')

@section('content')
    @include('components.supplier-product.supplier-product-create')
    @include('components.supplier-product.supplier-product-list')
    @include('components.supplier-product.supplier-product-update')
    @include('components.supplier-product.supplier-product-delete')
@overwrite