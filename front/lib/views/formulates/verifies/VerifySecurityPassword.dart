import 'package:flutter/material.dart';
import 'package:front/providers/AccountProvider.dart';
import 'package:provider/provider.dart';

Widget verifySecurityPassword() {
  return Selector<AccountProvider, int>(
      selector: (_, provider) => provider.hard,
      builder: (_, value, __) {
        return Row(
          children: () {
            List<Widget> listWidget = [];
            for (int i = 0; i < value; i++) {
              listWidget.add(
                Expanded(
                  flex: 9,
                  child: Container(
                    height: 5,
                    color: () {
                      switch (value) {
                        case 1:
                          return Colors.red;
                        case 2:
                          return Colors.orange[700];
                        case 3:
                          return Colors.orange;
                        case 4:
                          return Colors.yellow;
                        case 5:
                          return Colors.green;
                        default:
                          return Colors.transparent;
                      }
                    }(),
                  ),
                ),
              );
              if (i < 4) {
                listWidget.add(Spacer(
                  flex: 1,
                ));
              }
            }
            while (value < 5) {
              listWidget.add(
                Expanded(
                  flex: 9,
                  child: Container(
                    height: 5,
                    color: Colors.transparent,
                  ),
                ),
              );
              if (value < 4) {
                listWidget.add(Spacer(
                  flex: 1,
                ));
              }

              value++;
            }
            return listWidget;
          }(),
        );
      });
}
