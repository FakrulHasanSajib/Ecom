@php
$temvar = $rowIdsArray;
$ids=1;
$myprice = 0;
@endphp

<table class="cart_table table table-bordered table-striped text-center mb-0 carmys">

                                <tbody>
                                    @foreach($matchingData as $value)
                                <tr>
                                    <td>
                                        {{$ids++}}
                                    </td>
                                    <td class="text-left">
                                        <a style="font-size: 14px;" href="{{ route('product', $value->options->slug) }}">
                                            <img src="{{ asset($value->options->image) }}" height="30" width="30">
                                            {{ Str::limit($value->name, 20) }}
                                        </a>
                                    </td>
                                    <td width="15%" class="cart_qty">
                                        <div class="qty-cart vcart-qty">
                                            <div class="quantity">
                                                <button class="minus cart_decrement" data-id="{{ $value->rowId }}">-</button>
                                                <input type="text" value="{{ $value->qty }}" readonly />
                                                <button id="incerement" class="plus cart_increment" data-id="{{ $value->rowId }}">+</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>৳{{ $value->price * $value->qty }}</td>
                                    @php
                                    $myprice += $value->price * $value->qty;
                                    @endphp
                                </tr>
                            @endforeach
                            </tbody>
                            </table>
                            <table class="cart_total table table-bordered table-striped text-center mb-0">
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>মোট</strong></td>
                                <td><strong>৳{{ $myprice }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3"><strong>ডেলিভারি চার্জ</strong></td>
                                <td><strong>৳0</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3"><strong>সর্বমোট</strong></td>
                                <td><strong>৳{{ $myprice }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>

                            <script src="{{ asset('frontEnd/js/jquery-3.6.3.min.js') }}"></script>

<script>
    $(document).ready(function() {
    var rowIdsArray = <?php echo json_encode($temvar); ?>; // Initialize rowIdsArray from PHP data

    // Increment functionality with event delegation
    $(document).on("click", ".cart_increment", function () {
        var id = $(this).data("id");
        console.log("Increment button clicked, ID:", id);

        $("#loading").show();
        if (id) {
            // Perform the increment in the cart (AJAX call)
            $.ajax({
                type: "GET",
                data: { id: id },
                url: "{{ route('cart.myincrement') }}", // Ensure this URL is correct
                success: function (data) {
                    if (data) {
                        updateRowIdsArray(id, "increment"); // Update rowIdsArray after the increment
                        updatethisCart(); // Update the cart view
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        }
    });

    // Decrement functionality with event delegation
    $(document).on("click", ".cart_decrement", function () {
        var id = $(this).data("id");
        console.log("Decrement button clicked, ID:", id);

        $("#loading").show();
        if (id) {
            // Perform the decrement in the cart (AJAX call)
            $.ajax({
                type: "GET",
                data: { id: id },
                url: "{{ route('cart.mydecrement') }}", // Ensure this URL is correct
                success: function (data) {
                    if (data) {
                        updateRowIdsArray(id, "decrement"); // Update rowIdsArray after the decrement
                        updatethisCart(); // Update the cart view
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        }
    });

    function updateRowIdsArray(id, action) {
        if (!Array.isArray(rowIdsArray)) {
            rowIdsArray = [];
        }
        if (action === "increment") {
            // If increment, add the ID to the rowIdsArray
            if (!rowIdsArray.includes(id)) {
                rowIdsArray.push(id); // Add ID to the array if not already present
            }
        } else if (action === "decrement") {
            // If decrement, remove the ID from the rowIdsArray
            const index = rowIdsArray.indexOf(id);
            if (index !== -1) {
                rowIdsArray.splice(index, 1); // Remove ID from the array
            }
        }

        // Join the rowIds into a comma-separated string
        var rowIdsString = rowIdsArray.join(',');

        console.log("Updated rowIdsArray:", rowIdsString); // Check the updated array in the console

         // Return the updated string for use in the AJAX request
    }

    // Update cart view function
    function updatethisCart() {
        $.ajax({
            type: "POST",
            url: "{{ route('cart.newpro') }}", // This URL should handle the cart view update
            data: {
                temid: rowIdsArray, // Send the updated rowIdsArray to the backend
                _token: "{{ csrf_token() }}" // CSRF token for security
            },
            success: function(data) {
                if (data) {
                    $(".carmys").html(data); // Replace the cart HTML with the updated content
                    $("#loading").hide(); // Hide the loading spinner
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }
});

   </script>