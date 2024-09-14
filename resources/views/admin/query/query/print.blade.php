@include('admin.head-links')

<h2 class="text-center mt-5">Sample Specifications Sheet</h2>

<div class="table-responsive  mx-auto" style="width: 80%">
    <table class="table mt-5 mx-auto">
        <tbody>
            <tr>
                <td colspan="3"><strong>Buyer:</strong> {{ $specificationSheet->queryItem->queryModel->buyer->name }}
                </td>
                <td colspan="3"><strong>Factory:</strong> {{ $specificationSheet->factory->name }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Date:</strong>
                    {{ $specificationSheet->date ? $specificationSheet->date->format('d-m-Y') : '' }}</td>
                <td colspan="3"><strong>Contact Person:</strong>
                    {{ optional($specificationSheet->factory->contact_people->first())->name }}
                </td>
            </tr>
            <tr>
                <td colspan="3"><strong>App. Delivery Date:</strong>
                    {{ $specificationSheet->approximate_delivery_date ? $specificationSheet->approximate_delivery_date->format('d-m-Y') : '' }}
                </td>
                <td colspan="3"><strong>Phone:</strong>
                    {{ optional($specificationSheet->factory->contact_people->first())->phone }}
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Express Courier:</strong> {{ $specificationSheet->express_courier }} </td>
                <td colspan="2"><strong>AWB:</strong> {{ $specificationSheet->AWB }} </td>
                <td colspan="2"><strong>AWB Date:</strong>
                    {{ $specificationSheet->AWB_date ? $specificationSheet->AWB_date->format('d-m-Y') : '' }}
                </td>
            </tr>
            <tr>
                <td colspan="5"><strong>Item:</strong> {{ $specificationSheet->queryItem->name }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Model:</strong> {{ $specificationSheet->queryItem->product_model }} </td>
                <td colspan="2"><strong>Required Size:</strong> {{ $specificationSheet->required_size }} </td>
                <td colspan="2"><strong>Quantity:</strong> {{ $specificationSheet->quantity }} </td>
            </tr>
            <tr>
                <td colspan="3"><strong>Fitting:</strong> {{ $specificationSheet->fitting }} </td>
                <td colspan="3"><strong>Styling:</strong> {{ $specificationSheet->color }} </td>
            </tr>
            <tr>
                <td colspan="3"><strong>Required fabric composition:</strong>
                    {{ $specificationSheet->required_fabric_composition }}</td>
                <td colspan="3"><strong>GSM:</strong> {{ $specificationSheet->GSM }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Main label:</strong> {{ $specificationSheet->main_label }} </td>
                <td colspan="2"><strong>Care label:</strong> {{ $specificationSheet->care_label }} </td>
                <td colspan="2"><strong>Hang tag:</strong> {{ $specificationSheet->hang_tag }}</td>
            </tr>
            <tr>
                <td colspan="6"><strong>Print Instructions:</strong> {{ $specificationSheet->print_instructions }}
                </td>
            </tr>
            <tr>
                <td colspan="6"><strong>Embroidery Instructions:</strong>
                    {{ $specificationSheet->embroidery_instructions }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Button type:</strong> {{ $specificationSheet->button_type }}</td>
                <td colspan="2"><strong>Button size:</strong> {{ $specificationSheet->button_size }}</td>
                <td colspan="2"><strong>Button color:</strong> {{ $specificationSheet->button_color }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Button thread:</strong> {{ $specificationSheet->button_thread }}</td>
                <td colspan="3"><strong>Button hole:</strong> {{ $specificationSheet->button_hole }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Zipper type:</strong> {{ $specificationSheet->zipper_type }}</td>
                <td colspan="2"><strong>Zipper size:</strong> {{ $specificationSheet->zipper_size }}</td>
                <td colspan="2"><strong>Zipper color:</strong> {{ $specificationSheet->zipper_color }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Zipper thread:</strong> {{ $specificationSheet->zipper_thread }}</td>
                <td colspan="3"><strong>Zipper tape:</strong> {{ $specificationSheet->zipper_tape }}</td>
            </tr>
        </tbody>
    </table>

    <strong>Other Instructions:</strong>
    <p
        style="min-height: 200px; border: 1px solid black; padding: 8px; text-align: left; width: 100%; margin-top: 10px; margin-bottom: 10px;">
        {{ $specificationSheet->other_instructions }}</p>
</div>

<style>
    .table-responsive {
        overflow-x: auto;

    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 2px 4px !important;
        text-align: left;
        font-size: 12px;
    }



    @media (max-width: 768px) {
        table {
            font-size: 14px;
        }

        th,
        td {
            padding: 5px;
        }
    }
</style>

@include('admin.scripts')

<script>
    window.onload = function() {
        window.print();
    }
</script>
