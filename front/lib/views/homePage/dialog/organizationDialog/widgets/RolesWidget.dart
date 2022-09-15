import 'package:flutter/material.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/providers/ListFoldersProvider.dart';
import 'package:front/views/homePage/dialog/organizationDialog/widgets/ListFoldersWidget.dart';

Widget rolesWidget(
    {required Organization organization, required BuildContext context}) {
  return Row(
    children: [
      Expanded(
        flex: 3,
        child: ListFoldersWidget(context: context, organization: organization),
      ),
      Expanded(
        flex: 7,
        child: ListFoldersWidget(context: context, organization: organization),
      ),
    ],
  );
}
