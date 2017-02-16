<table class="table table-striped">
    <tbody>
    <tr>
        <td class="col-md-4">
            <label>Recommend</label>
        </td>
        <td></td>
    </tr>
    @foreach($recommend_profile as $object)
    <tr>
        <td class="col-md-4" style="vertical-align:top">
            Via Hub Files
        </td>
        <td>
            <ol>
            @foreach($object['data'] as $item)
                <li>
                    <a href="{{ $item->getOperatorLink() }}" target="_blank" style="color: #428bca">#{{ $item->getOperatorName() }}</a>
                    {{ $item->action() }} {{ $item->object() }}
                    <a href="{{ $item->objectLink() }}" target="_blank" style="color: #428bca">
                        @if ($item->object() == 'member')
                            #{{ $item->objectName() }}
                        @else
                            #{{ $item->objectId() }}
                        @endif
                    </a>
                    to {{ $item->receiveType() }}
                    <a href="{{ $item->receiverLink() }}" target="_blank" style="color: #428bca">
                        @if ($item->receiveType() == 'member')
                            #{{ $item->receiverName() }}
                        @else
                            #{{ $item->receiverId() }}
                        @endif
                    </a>
                    on {{ $item->textOnTime() }}
                </li>
            @endforeach
            </ol>
        </td>
    </tr>
    @endforeach
    @foreach($recommend_project as $object)
    <tr>
        <td class="col-md-4" style="vertical-align:top">
            P: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
        </td>
        <td>
            <ol>
            @foreach($object['data'] as $item)
                <li>
                    <a href="{{ $item->getOperatorLink() }}" target="_blank" style="color: #428bca">#{{ $item->getOperatorName() }}</a>
                    {{ $item->action() }} {{ $item->object() }}
                    <a href="{{ $item->objectLink() }}" target="_blank" style="color: #428bca">
                        @if ($item->object() == 'member')
                            #{{ $item->objectName() }}
                        @else
                            #{{ $item->objectId() }}
                        @endif
                    </a>
                    to {{ $item->receiveType() }}
                    <a href="{{ $item->receiverLink() }}" target="_blank" style="color: #428bca">
                        @if ($item->receiveType() == 'member')
                            #{{ $item->receiverName() }}
                        @else
                            #{{ $item->receiverId() }}
                        @endif
                    </a>
                    on {{ $item->textOnTime() }}
                </li>
            @endforeach
            </ol>
        </td>
    </tr>
    @endforeach
    @foreach($recommend_solution as $object)
        <tr>
            <td class="col-md-4" style="vertical-align:top">
                S: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
            </td>
            <td>
                <ol>
                @foreach($object['data'] as $item)
                    <li>
                        <a href="{{ $item->getOperatorLink() }}" target="_blank" style="color: #428bca">#{{ $item->getOperatorName() }}</a>
                        {{ $item->action() }} {{ $item->object() }}
                        <a href="{{ $item->objectLink() }}" target="_blank" style="color: #428bca">
                            @if ($item->object() == 'member')
                                #{{ $item->objectName() }}
                            @else
                                #{{ $item->objectId() }}
                            @endif
                        </a>
                        to {{ $item->receiveType() }}
                        <a href="{{ $item->receiverLink() }}" target="_blank" style="color: #428bca">
                            @if ($item->receiveType() == 'member')
                                #{{ $item->receiverName() }}
                            @else
                                #{{ $item->receiverId() }}
                            @endif
                        </a>
                        on {{ $item->textOnTime() }}
                    </li>
                @endforeach
                </ol>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="table table-striped">
    <tbody>
    <tr>
        <td class="col-md-4">
            <label>Referrals</label>
        </td>
        <td></td>
    </tr>
    <tr>
        <td class="col-md-4" style="vertical-align:top">
            Via Hub Files
        </td>
        <td>
            <ol>
            @foreach($referrals_profile as $object)
                @foreach($object['data'] as $item)
                    <li>
                        <a href="{{ $item->getOperatorLink() }}" target="_blank" style="color: #428bca">#{{ $item->getOperatorName() }}</a>
                        {{ $item->action() }} {{ $item->object() }}
                        <a href="{{ $item->objectLink() }}" target="_blank" style="color: #428bca">
                            @if ($item->object() == 'member')
                                #{{ $item->objectName() }}
                            @else
                                #{{ $item->objectId() }}
                            @endif
                        </a>
                        to {{ $item->receiveType() }}
                        <a href="{{ $item->receiverLink() }}" target="_blank" style="color: #428bca">
                            @if ($item->receiveType() == 'member')
                                #{{ $item->receiverName() }}
                            @else
                                #{{ $item->receiverId() }}
                            @endif
                        </a>
                        on {{ $item->textOnTime() }}
                    </li>
                @endforeach
            @endforeach
            </ol>
        </td>
    </tr>
    @foreach($referrals_project as $object)
        <tr>
            <td class="col-md-4" style="vertical-align:top">
                P: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
            </td>
            <td>
                <ol>
                @foreach($object['data'] as $item)
                    <li>
                        <a href="{{ $item->getOperatorLink() }}" target="_blank" style="color: #428bca">#{{ $item->getOperatorName() }}</a>
                        {{ $item->action() }} {{ $item->object() }}
                        <a href="{{ $item->objectLink() }}" target="_blank" style="color: #428bca">
                            @if ($item->object() == 'member')
                                #{{ $item->objectName() }}
                            @else
                                #{{ $item->objectId() }}
                            @endif
                        </a>
                        to {{ $item->receiveType() }}
                        <a href="{{ $item->receiverLink() }}" target="_blank" style="color: #428bca">
                            @if ($item->receiveType() == 'member')
                                #{{ $item->receiverName() }}
                            @else
                                #{{ $item->receiverId() }}
                            @endif
                        </a>
                        on {{ $item->textOnTime() }}
                    </li>
                @endforeach
                </ol>
            </td>
        </tr>
    @endforeach
    @foreach($referrals_solution as $object)
        <tr>
            <td class="col-md-4" style="vertical-align:top">
                S: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
            </td>
            <td>
                <ol>
                @foreach($object['data'] as $item)
                    <li>
                    <a href="{{ $item->getOperatorLink() }}" target="_blank" style="color: #428bca">#{{ $item->getOperatorName() }}</a>
                    {{ $item->action() }} {{ $item->object() }}
                    <a href="{{ $item->objectLink() }}" target="_blank" style="color: #428bca">
                        @if ($item->object() == 'member')
                            #{{ $item->objectName() }}
                        @else
                            #{{ $item->objectId() }}
                        @endif
                    </a>
                    to {{ $item->receiveType() }}
                    <a href="{{ $item->receiverLink() }}" target="_blank" style="color: #428bca">
                        @if ($item->receiveType() == 'member')
                            #{{ $item->receiverName() }}
                        @else
                            #{{ $item->receiverId() }}
                        @endif
                    </a>
                    on {{ $item->textOnTime() }}
                    </li>
                @endforeach
                </ol>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
