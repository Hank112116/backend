<table class="table table-striped">
    <tbody>
    <tr>
        <td class="col-md-4">
            <label>Recommend</label>
        </td>
        <td></td>
    </tr>
    <?php $i = 1 ?>
    @foreach($recommend_profile as $object)
    <tr>
        <td class="col-md-4">
            Via Hub Files
        </td>
        <td>
            @foreach($object['data'] as $item)
                {{ $i }}.
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
                @if (!$loop->last)
                    <br/>
                @endif
                <?php $i ++ ?>
            @endforeach
        </td>
    </tr>
    @endforeach
    @foreach($recommend_project as $object)
    <tr>
        <td class="col-md-4">
            P: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
        </td>
        <td>
            @foreach($object['data'] as $item)
                {{ $i }}.
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

                @if (!$loop->last)
                    <br/>
                @endif
                <?php $i ++ ?>
            @endforeach
        </td>
    </tr>
    @endforeach
    @foreach($recommend_solution as $object)
        <tr>
            <td class="col-md-4">
                S: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
            </td>
            <td>
                @foreach($object['data'] as $item)
                    {{ $i }}.
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

                    @if (!$loop->last)
                        <br/>
                    @endif
                    <?php $i ++ ?>
                @endforeach
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
    <?php $i = 1 ?>
    @foreach($referrals_profile as $object)
    <tr>
        <td class="col-md-4">
            Via Hub Files
        </td>
        <td>
            @foreach($object['data'] as $item)
                {{ $i }}.
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

                @if (!$loop->last)
                    <br/>
                @endif
                <?php $i ++ ?>
            @endforeach
        </td>
    </tr>
    @endforeach

    @foreach($referrals_project as $object)
        <tr>
            <td class="col-md-4">
                P: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
            </td>
            <td>
                @foreach($object['data'] as $item)
                    {{ $i }}.
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

                    @if (!$loop->last)
                        <br/>
                    @endif
                    <?php $i++ ?>
                @endforeach
            </td>
        </tr>
    @endforeach

    @foreach($referrals_solution as $object)
        <tr>
            <td class="col-md-4">
                S: <a href="{{ $object['object_link'] }}" target="_blank" style="color: #428bca">{{ $object['object_name'] }} #{{ $object['object_id'] }}</a>
            </td>
            <td>
                @foreach($object['data'] as $item)
                    {{ $i }}.
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

                    @if (!$loop->last)
                        <br/>
                    @endif
                    <?php $i ++ ?>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
