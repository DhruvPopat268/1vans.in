@php
    $userPlan = \App\Models\Plan::getPlan(\Auth::user()->show_dashboard());
            $webAccess = is_array(Auth::user()->web_access) ? Auth::user()->web_access : json_decode(Auth::user()->web_access ?? '[]', true);

@endphp
{{Form::model($role,array('route' => array('roles.update', $role->id), 'method' => 'PUT', 'class'=>'needs-validation', 'novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Role Name'), 'required' => 'required', (isset($role->name) && $role->name == 'Employee') ? 'readonly' : ''))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="background-color: #999f9f;border-radius: 17px;">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-dashboard-tab" data-bs-toggle="pill" href="#dashboard" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Dashboard')}}</a>
                </li>
              
                   @if(in_array('manage site documents', $webAccess) ||
    in_array('manage working drawings', $webAccess) ||
    in_array('manage bill of quantity', $webAccess) ||
    in_array('manage material testing reports', $webAccess) ||
    in_array('manage autocad files', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-project-document-tab" data-bs-toggle="pill" href="#project-document" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Project Documents')}}</a>
                    </li>
                @endif
                  @if(in_array('manage types of work', $webAccess) ||
    in_array('manage name of works', $webAccess) ||
    in_array('manage working area', $webAccess) ||
    in_array('manage types of measurements', $webAccess) ||
    in_array('manage work reports', $webAccess) ||
    in_array('manage all reports', $webAccess) ||
    in_array('manage man power', $webAccess) ||
    in_array('manage working agency', $webAccess) ||
    in_array('manage import data', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-project-management-tab" data-bs-toggle="pill" href="#project-management" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Project Management')}}</a>
                    </li>
                @endif

                 @if(in_array('manage types of equipment', $webAccess) ||
    in_array('manage equipments summary', $webAccess) ||
    in_array('manage equipments reports', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-equipment-management-tab" data-bs-toggle="pill" href="#equipment-management" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Equipment Management')}}</a>
                    </li>
                @endif

                 @if(in_array('manage types of material', $webAccess) ||
    in_array('manage material units', $webAccess) ||
    in_array('manage material record', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-material-reports-tab" data-bs-toggle="pill" href="#material-reports" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Material Reports')}}</a>
                    </li>
                @endif

                 @if(in_array('manage todo list', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-todo-list-tab" data-bs-toggle="pill" href="#todo-list" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('ToDO List')}}</a>
                    </li>
                @endif

           @if(in_array('manage work issue', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-work-issue-tab" data-bs-toggle="pill" href="#work-issue" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Work Issue')}}</a>
                    </li>
                @endif
               @if(in_array('manage site gallery', $webAccess))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-site-gallery-tab" data-bs-toggle="pill" href="#site-gallery" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Site Gallery')}}</a>
                    </li>
                @endif
                
                @if (!empty($userPlan) && $userPlan->crm == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="pills-crm-tab" data-bs-toggle="pill" href="#crm" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('CRM')}}</a>
                    </li>
                @endif
                {{-- @if (!empty($userPlan) && $userPlan->project == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="pills-project-tab" data-bs-toggle="pill" href="#project" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Project')}}</a>
                    </li>
                @endif --}}
                @if (!empty($userPlan) && $userPlan->hrm == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="pills-hrmpermission-tab" data-bs-toggle="pill" href="#hrmpermission" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('HRM')}}</a>
                    </li>
                @endif
                @if (!empty($userPlan) && $userPlan->account == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="pills-account-tab" data-bs-toggle="pill" href="#account" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Account')}}</a>
                    </li>
                @endif
                @if (!empty($userPlan) && $userPlan->pos == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="pills-account-tab" data-bs-toggle="pill" href="#pos" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('POS')}}</a>
                    </li>
                @endif

            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="pills-home-tab">
                    @php
                        $modules=['project dashboard','graph dashboard'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Dashboard Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="dashboard_checkall" id="dashboard_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck dashboard_checkall"  data-id="{{str_replace(' ', '', str_replace('&', '', $module))}}" ></td>
                                            <td><label class="ischeck dashboard_checkall" data-id="{{str_replace(' ', '', str_replace('&', '', $module))}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input dashboard_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="project-document" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['site documents','working drawings','bill of quantity','material testing reports','autocad files'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Project Documents related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="project-document_checkall"  id="project-document_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck project-document_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck project-document_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-document_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="project-management" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['types of work','name of works','working area','types of measurements','work reports','all reports','man power'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Project Management related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="project-management_checkall"  id="project-management_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck project-management_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck project-management_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    
                                                    @if(in_array('download '.$module,(array) $permissions))
                                                        @if($key = array_search('download '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Download',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="equipment-management" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['types of equipment','equipments summary','equipments reports'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Equipment Management related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="equipment-management_checkall"  id="equipment-management_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck equipment-management_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck equipment-management_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('download '.$module,(array) $permissions))
                                                        @if($key = array_search('download '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Download',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input equipment-management_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="material-reports" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['material units','types of material','material record','material incoming','material order'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Material Reports related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="material-reports_checkall"  id="material-reports_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck material-reports_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck material-reports_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('download '.$module,(array) $permissions))
                                                        @if($key = array_search('download '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Download',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input material-reports_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="todo-list" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['todo list'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign ToDO List related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="todo-list_checkall"  id="todo-list_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck todo-list_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck todo-list_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('download '.$module,(array) $permissions))
                                                        @if($key = array_search('download '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Download',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input todo-list_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                  <div class="tab-pane fade" id="work-issue" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['work issue'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Work Issue related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="work-issue_checkall"  id="work-issue_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck work-issue_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck work-issue_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('download '.$module,(array) $permissions))
                                                        @if($key = array_search('download '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Download',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input work-issue_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="site-gallery" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['site gallery'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Site Gallery related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="site-gallery_checkall"  id="site-gallery_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck site-gallery_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck site-gallery_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('download '.$module,(array) $permissions))
                                                        @if($key = array_search('download '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Download',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input site-gallery_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="crm" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @php
                        $modules=['crm dashboard','lead','pipeline','lead stage','source','label','lead email','lead call','deal','stage','task','form builder','form response', 'form field', 'contract','contract type'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign CRM related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="crm_checkall"  id="crm_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck crm_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck crm_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input crm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="project" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['project dashboard','project','milestone','grant chart','project stage','timesheet','project expense','project task','activity','CRM activity','project task stage','bug report','bug status'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Project related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="project_checkall"  id="project_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck project_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck project_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="hrmpermission" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['hrm dashboard','employee','employee profile','department','designation','branch','document type','document','payslip type','allowance','commission','allowance option','loan option','deduction option','loan','saturation deduction','other payment','overtime','set salary','pay slip','company policy','appraisal','goal tracking','goal type','indicator','event','meeting','training','trainer','training type','award','award type','resignation','travel','promotion','complaint','warning','termination','termination type','job application','job application note','job onBoard','job category','job','job stage','custom question','interview schedule','career','estimation','holiday','transfer','announcement','leave','leave type','attendance'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign HRM related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="hrm_checkall"  id="hrm_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck hrm_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck hrm_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input hrm_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['account dashboard','proposal','invoice','bill','revenue','payment','proposal product','invoice product','bill product','goal','credit note','debit note','bank account','bank transfer','transaction','customer','vender','constant custom field','assets','chart of account','journal entry','report'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Account related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="account_checkall"  id="account_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck account_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck account_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('convert '.$module,(array) $permissions))
                                                    @if($key = array_search('convert '.$module,$permissions))
                                                        <div class="col-md-3 custom-control custom-checkbox">
                                                            {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input isscheck account_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'convert',['class'=>'custom-control-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pos" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['pos dashboard', 'warehouse','quotation','purchase','pos','barcode'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign POS related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="pos_checkall"  id="pos_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>

                                            <td><input type="checkbox" class="form-check-input align-middle ischeck pos_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck pos_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>

                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('convert '.$module,(array) $permissions))
                                                    @if($key = array_search('convert '.$module,$permissions))
                                                        <div class="col-md-3 custom-control custom-checkbox">
                                                            {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'convert',['class'=>'custom-control-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>

{{Form::close()}}

<script>
    $(document).ready(function () {
        $("#dashboard_checkall").click(function(){
            $('.dashboard_checkall').not(this).prop('checked', this.checked);
        });
                $("#project-document_checkall").click(function(){
            $('.project-document_checkall').not(this).prop('checked', this.checked);
        });
          $("#project-management_checkall").click(function(){
            $('.project-management_checkall').not(this).prop('checked', this.checked);
        });
         $("#equipment-management_checkall").click(function(){
            $('.equipment-management_checkall').not(this).prop('checked', this.checked);
        });
                          $("#material-reports_checkall").click(function(){
            $('.material-reports_checkall').not(this).prop('checked', this.checked);
        });
                     $("#todo-list_checkall").click(function(){
            $('.todo-list_checkall').not(this).prop('checked', this.checked);
        });
         $("#work-issue_checkall").click(function(){
            $('.work-issue_checkall').not(this).prop('checked', this.checked);
        });
         $("#site-gallery_checkall").click(function(){
            $('.site-gallery_checkall').not(this).prop('checked', this.checked);
        });
        $("#crm_checkall").click(function(){
            $('.crm_checkall').not(this).prop('checked', this.checked);
        });
        $("#project_checkall").click(function(){
            $('.project_checkall').not(this).prop('checked', this.checked);
        });
        $("#hrm_checkall").click(function(){
            $('.hrm_checkall').not(this).prop('checked', this.checked);
        });
        $("#account_checkall").click(function(){
            $('.account_checkall').not(this).prop('checked', this.checked);
        });
        $("#pos_checkall").click(function(){
            $('.pos_checkall').not(this).prop('checked', this.checked);
        });
        $(".ischeck").click(function(){
            var ischeck = $(this).data('id');
            $('.isscheck_'+ ischeck).prop('checked', this.checked);
        });
    });
</script>
