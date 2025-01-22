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
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function isMessageSendable(MessageSendingContextTransfer $messageSendingContextTransfer): bool;
}
