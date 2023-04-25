<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- B X-CSRF-TOKEN documentaion laravel --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- E X-CSRF-TOKEN documentaion laravel --}}


    <title>CRUD AJAX</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.css" rel="stylesheet" />



    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>

    <div class="container mx-auto text-center">
        <p class="text-4xl">LARAVEL CRUD AJAX</p>

        <div class="relative overflow-x-auto shadow-md ">

            <div class="pb-4 py-6 bg-white dark:bg-gray-900">
                <!-- Modal toggle -->
                <div class="flex justify-center m-5">
                    <button id="defaultModalButton" data-modal-toggle="modal_add"
                        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                        Create product
                    </button>
                </div>
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <input type="text" id="table-search"
                        class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for items">
                </div>
            </div>
            <table class="table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product as $key => $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $key + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $item->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->price }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}" data-modal-toggle="modal_update" class="edit_product font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <a href="#" data-id="{{ $item->id }}" class="delete_product font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    {{ $product->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    {{-- B include Models --}}
    @include('components.modal-add')
    @include('components.modal-update')
    {{-- E include Models --}}


    {{-- B script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.js"></script>

    {{-- B jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    {{-- E jquery --}}

    {{-- B X-CSRF-TOKEN documentaion laravel --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    {{-- E X-CSRF-TOKEN documentaion laravel --}}

    <script>
        $(document).ready(function() {
            // create
            $(document).on('click', '.add_product', function(e) {
                var name = $('#name').val();
                var price = $('#price').val();
                // console.log(name +' '+price);

                $.ajax({
                    url: "{{ route('products.store') }}",
                    method: 'Post',
                    data: {
                        name: name,
                        price: price
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.table').load(location.href + ' .table');
                            console.log($('#modal_add').modal('hide'));
                        }
                    },
                    error: function(err) {
                        let error = err.responseJSON;
                        $.each(error.errors, function(index, value) {
                            $('#errMsgContainer').append('<span class="text-red-600">' + value + '</span><br>')
                        })
                    }
                })
            });

            //edit
            $(document).on('click', '.edit_product', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let price = $(this).data('price');

                $('#id_product').val(id);
                $('#up_name').val(name);
                $('#up_price').val(price);
            });

            // update
            $(document).on('click', '.update_product', function(e) {
                var id = $('#id_product').val();
                var name = $('#up_name').val();
                var price = $('#up_price').val();
                // console.log(price);

                $.ajax({
                    url: "{{ route('products.update', '') }}/" + id,
                    method: 'PUT',
                    data: {
                        id: id,
                        name: name,
                        price: price,
                        // _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.table').load(location.href + ' .table');
                        }
                    },
                    error: function(err) {
                        let error = err.responseJSON;
                        $.each(error.errors, function(index, value) {
                            $('#errMsgContainer').append('<span class="text-red-600">' + value + '</span><br>')
                        })
                    }
                })
            });

            // delete
            $(document).on('click', '.delete_product', function(e) {
                let id = $(this).data('id');

                if (confirm("Are you sure!")) {
                    $.ajax({
                        url: "{{ route('products.destroy', '') }}/" + id,
                        method: 'DELETE',
                        data: {
                            id: id,
                            // _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                $('.table').load(location.href + ' .table');
                            }
                        },
                        error: function(err) {
                            let error = err.responseJSON;
                            $.each(error.errors, function(index, value) {
                                $('#errMsgContainer').append('<span class="text-red-600">' + value + '</span><br>')
                            })
                        }
                    })
                }

            });
        });
    </script>
    {{-- E script --}}

</body>

</html>
