<div class="container-fluid mt-4">
    @include('livewire.admin.modal.modal-approval-restaurant')
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __("Admin - Approval Restaurant") }}</h3></div>
        <div class="card-body">
            <table id="demo-foo-addrow"
                class="table table-bordered table-striped"
                data-paging="true" data-paging-size="7" style="">
                <thead>
                    <tr class="footable-header">
                        <th style="display: table-cell;">{{ __("Bil") }}</th>
                        <th style="display: table-cell;">{{ __("Name") }}</th>
                        <th style="display: table-cell;">{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($restaurantApproval->isNotEmpty())
                        @foreach ($restaurantApproval as $approvalRestaurant)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$approvalRestaurant->name}}</td>                            
                                <td>
                                    <button class="btn btn-success btn-circle" wire:click="modalApprovalRestaurant({{$approvalRestaurant->id}})" data-bs-toggle="modal" data-bs-target="#modalApprovalRestaurant">
                                        <span class="bi bi-pencil-fill">Approval</span>
                                    </button>                                
                                </td>                            
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">{{ __("No restaurant approval.") }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>