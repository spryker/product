<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageSendingContextTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ProductToMessageBrokerInterface
{
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer;

    public function isMessageSendable(MessageSendingContextTransfer $messageSendingContextTransfer): bool;
}
